<?php

namespace App\Http\Controllers\Api;

// Modul
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\PersonalAccessToken;
use Carbon\Carbon;

// Model
use App\Http\Controllers\Controller;
use App\ModelCG\Schedule;
use App\ModelCG\ScheduleBackup;
use App\ModelCG\Project;
use App\Absen;
use App\Payrol;
use App\Payrollns;
use App\ModelCG\Payroll;
use App\Employee;
use App\User;
use App\Absen\RequestAbsen;
use App\Company\CompanyModel;
use App\Absen\RequestType;
use App\Slack;
use App\ModelCG\Shift;
use App\Backup\AbsenBackup;
use App\Payrol\Component;
use App\Urbanica\PayrolUrbanica;
use App\ModelCG\Datamaster\ProjectShift;

class ApiLoginController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
        $cek = Employee::where('email', $request->email)->where('resign_status',0)->first();
        if(!$cek){
            return response()->json([
                'success' => false,
                'message' => 'Login Failed!',
            ]);
        }
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Login Failed!',
            ]);
        }


        // Cek dan simpan UUID tes
        if (empty($user->uuid)) {
            $user->uuid = $request->uuid;
            $user->save();
        } else {
            // UUID sudah tersimpan, cek apakah cocok
            if ($user->uuid !== $request->uuid) {
                return response()->json([
                    'success' => false,
                    'message' => 'Login ditolak. Akun ini sudah digunakan di perangkat lain.',
                ], 403);
            }
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Login Success!',
            'data'    => $user,
            'token'   => $user->createToken('authToken')->accessToken    
        ]);
    }

    public function logout(Request $request)
    {
        $removeToken = $request->user()->tokens()->delete();

        if($removeToken) {
            return response()->json([
                'success' => true,
                'message' => 'Logout Success!',  
            ]);
        }
    }

    // Absen Masuk
    public function clockin(Request $request)
    {
        $token = $request->bearerToken();
        $user = Auth::guard('api')->user();
        $nik = $user->employee_code;
        $unit_bisnis = Employee::where('nik', $nik)->first();
        $today = now()->toDateString();

        DB::beginTransaction();

        // Validasi UUID perangkats
        $incomingUUID = $request->input('uuid'); 
        if ($user->uuid === null) {
            $user->uuid = $incomingUUID;
            $user->save();
        } elseif ($user->uuid !== $incomingUUID) {
            return response()->json([
                'message' => 'Clock In ditolak! Akun ini hanya bisa digunakan di 1 perangkat.',
                'success' => false
            ], 403);
        }

        // Ambil schedule & backup
        $Schedule = Schedule::where('employee', $nik)
            ->whereDate('tanggal', $today)
            ->first();

        $schedulebackup = ScheduleBackup::where('employee', $nik)
            ->whereDate('tanggal', $today)
            ->first();

        // Cek apakah sudah absen
        $existingAbsen = Absen::where('nik', $nik)
            ->whereDate('tanggal', $today)
            ->first();

        $existingAbsenBackup = AbsenBackup::where('nik', $nik)
            ->whereDate('tanggal', $today)
            ->first();

        if ($existingAbsen || $existingAbsenBackup) {
            DB::rollBack();
            return response()->json([
                'message' => 'Absen Ditolak, sudah ada absensi hari ini!',
                'success' => false,
            ], 200);
        }

        // Ambil data lokasi & radius berdasarkan schedule
        if ($Schedule) {
            $dataProject = Project::find($Schedule->project);
            $projectData = $dataProject->id ?? 123;
            $kantorLatitude = $dataProject->latitude;
            $kantorLongitude = $dataProject->longtitude;
            $allowedRadius = 5;
        } elseif ($schedulebackup) {
            $dataCompany = CompanyModel::where('company_name', $unit_bisnis->unit_bisnis)->first();
            $projectData = $schedulebackup->project;
            $kantorLatitude = $dataCompany->latitude;
            $kantorLongitude = $dataCompany->longitude;
            $allowedRadius = $dataCompany->radius;
        } else {
            DB::rollBack();
            return response()->json([
                'message' => 'Tidak ada schedule dan tidak ada backup schedule!',
                'success' => false,
            ], 200);
        }

        // Tambahan khusus DRIVER
        if ($unit_bisnis->jabatan == 'DRIVER') {
            $allowedRadius = 9999999;
        }

        // Validasi khusus untuk unit "Kas" dan organisasi "Frontline Officer"
        if (
            strcasecmp($unit_bisnis->unit_bisnis, 'Kas') == 0 &&
            strcasecmp($unit_bisnis->organisasi, 'FRONTLINE OFFICER') == 0
        ) {
            if (!$Schedule) {
                DB::rollBack();
                return response()->json([
                    'message' => 'Absen Masuk Ditolak, Tidak ada schedule! Hubungi team leader.',
                    'success' => false,
                ], 200);
            } elseif ($Schedule->shift === 'OFF') {
                DB::rollBack();
                return response()->json([
                    'message' => 'Absen Masuk Ditolak, Schedule OFF. Hubungi team leader.',
                    'success' => false,
                ], 200);
            }
        }

        // Ambil koordinat user
        $lat = $request->input('latitude');
        $long = $request->input('longitude');
        $status = $request->input('status');

        // Cek jarak GPS
        $distance = $this->calculateDistance($kantorLatitude, $kantorLongitude, $lat, $long);

        if ($distance <= $allowedRadius) {
            // Proses upload foto
            $filename = null;
            if ($request->hasFile('photo')) {
                $image = $request->file('photo');
                $filename = time() . '.' . $image->getClientOriginalExtension();
                $path = $image->storeAs('images/absen', $filename, 'public');
            }

            // Validasi shift untuk KAS
            $shift_fix = 1; // default lolos shift

            if (
                ($unit_bisnis->unit_bisnis == "Kas" || $unit_bisnis->unit_bisnis == "KAS") &&
                $Schedule
            ) {
                $cek = ProjectShift::where('shift_code', $Schedule->shift)
                    ->where('project_id', $Schedule->project)
                    ->get();

                $nowTime = now()->format('H:i');
                $shift_fix = 0;
                $jam_masuk = '';
                $jam_pulang = '';

                foreach ($cek as $row) {
                    $jam_masuk = $row->jam_masuk;
                    $jam_pulang = $row->jam_pulang;

                    if ($jam_pulang < $jam_masuk) {
                        if ($nowTime >= $jam_masuk || $nowTime <= $jam_pulang) {
                            $shift_fix = 1;
                            break;
                        }
                    } else {
                        if ($nowTime >= $jam_masuk && $nowTime <= $jam_pulang) {
                            $shift_fix = 1;
                            break;
                        }
                    }
                }

                if ($shift_fix == 0) {
                    DB::rollBack();
                    return response()->json([
                        'message' => "Anda tidak bisa clock in karena schedule {$Schedule->shift} ($jam_masuk - $jam_pulang)",
                        'success' => false,
                    ]);
                }
            }

            // Simpan ke tabel yang sesuai
            if ($Schedule) {
                $insert = Absen::create([
                    'user_id' => $nik,
                    'nik' => $nik,
                    'project' => $projectData,
                    'tanggal' => $today,
                    'clock_in' => now()->format('H:i'),
                    'latitude' => $lat,
                    'longtitude' => $long,
                    'status' => $status,
                    'photo' => $filename
                ]);
            } else {
                $insert = AbsenBackup::create([
                    'user_id' => $nik,
                    'nik' => $nik,
                    'project' => $projectData,
                    'tanggal' => $today,
                    'clock_in' => now()->format('H:i'),
                    'latitude' => $lat,
                    'longtitude' => $long,
                    'status' => $status,
                    'photo' => $filename,
                    'notes' => 'Clock-in via backup schedule'
                ]);
            }

            DB::commit();
            return response()->json([
                'message' => 'Absen Masuk Berhasil, Selamat Bekerja!',
                'success' => true,
                'data' => $insert
            ]);
        } else {
            DB::rollBack();
            return response()->json([
                'message' => 'Absen Masuk Gagal, Diluar Radius!',
                'success' => false,
            ]);
        }
    }


    private function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371; 

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLon / 2) * sin($dLon / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        $distance = $earthRadius * $c; 

        return $distance;
    }

    // Absen Balik
    public function clockout(Request $request)
    {
        try {
            $token = $request->bearerToken();
            $user = Auth::guard('api')->user();
            $nik = $user->employee_code;
            $unit_bisnis = Employee::where('nik', $nik)->first();

            $incomingUUID = $request->input('uuid');

            // Validasi UUID perangkat
            if ($user->uuid === null) {
                $user->uuid = $incomingUUID;
                $user->save();
            } elseif ($user->uuid !== $incomingUUID) {
                return response()->json([
                    'message' => 'Clock Out ditolak! Akun ini hanya bisa digunakan di 1 perangkat.',
                    'success' => false
                ], 403);
            }

            $lat2 = $request->input('latitude_out');
            $long2 = $request->input('longitude_out');

            $today = now()->toDateString();
            $yesterday = now()->subDay()->toDateString();

            // Cek shift kemarin (khusus ML)
            $scheduleYesterday = Schedule::where('employee', $nik)
                ->whereDate('tanggal', $yesterday)
                ->first();

            if ($scheduleYesterday && strcasecmp($scheduleYesterday->shift, 'ML') == 0) {
                $absenYesterday = Absen::where('nik', $nik)
                    ->whereDate('tanggal', $yesterday)
                    ->first();

                if ($absenYesterday) {
                    $absenYesterday->clock_out = now()->format('H:i');
                    $absenYesterday->latitude_out = $lat2;
                    $absenYesterday->longtitude_out = $long2;
                    $absenYesterday->save();

                    return response()->json([
                        'success' => true,
                        'message' => 'Clockout success untuk shift ML! Selamat Beristirahat!',
                    ], 200);
                }

                // Cek di backup jika absen utama tidak ada
                $absenYesterdayBackup = AbsenBackup::where('nik', $nik)
                    ->whereDate('tanggal', $yesterday)
                    ->first();

                if ($absenYesterdayBackup) {
                    $absenYesterdayBackup->clock_out = now()->format('H:i');
                    $absenYesterdayBackup->latitude_out = $lat2;
                    $absenYesterdayBackup->longtitude_out = $long2;
                    $absenYesterdayBackup->save();

                    return response()->json([
                        'success' => true,
                        'message' => 'Clockout shift ML berhasil (backup)!',
                    ], 200);
                }

                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ditemukan absen clock-in kemarin (shift ML)',
                ], 404);
            }

            // Proses clock-out untuk hari ini
            $absenToday = Absen::where('nik', $nik)
                ->whereDate('tanggal', $today)
                ->first();

            if ($absenToday) {
                $absenToday->clock_out = now()->format('H:i');
                $absenToday->latitude_out = $lat2;
                $absenToday->longtitude_out = $long2;
                $absenToday->save();

                return response()->json([
                    'success' => true,
                    'message' => 'Clockout hari ini berhasil! Selamat Beristirahat!',
                ], 200);
            }

            // Cek jika masuk via AbsenBackup
            $absenTodayBackup = AbsenBackup::where('nik', $nik)
                ->whereDate('tanggal', $today)
                ->first();

            if ($absenTodayBackup) {
                $absenTodayBackup->clock_out = now()->format('H:i');
                $absenTodayBackup->latitude_out = $lat2;
                $absenTodayBackup->longtitude_out = $long2;
                $absenTodayBackup->save();

                return response()->json([
                    'success' => true,
                    'message' => 'Clockout (backup) hari ini berhasil! Selamat Beristirahat!',
                ], 200);
            }

            return response()->json([
                'success' => false,
                'message' => 'Clock-in belum dilakukan, tidak bisa clock-out.',
            ], 404);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }

    // Backup Absen
    public function clockinbackup(Request $request)
    {   
        try {
            $token = $request->bearerToken();
            $user = Auth::guard('api')->user();
            $nik = $user->employee_code;
            $unit_bisnis = Employee::where('nik',$nik)->first();

            $today = Carbon::now()->format('Y-m-d');

            $schedulebackup = ScheduleBackup::where('employee', $nik)
                ->whereDate('tanggal', $today)
                ->get();

            foreach($schedulebackup as $databackup)
            {
                $project_id = $databackup->project;
                $tanggal_backup = $databackup->tanggal;
                $shift = $databackup->shift;
                $periode = $databackup->periode;
            }

            

            $dataProject = Project::where('id', $project_id)->first();

            $latitudeProject = $dataProject->latitude;
            $longtitudeProject = $dataProject->longtitude;
            
            $kantorLatitude = $latitudeProject;
            $kantorLongtitude = $longtitudeProject;

            $time_in = Carbon::now()->format('H:i');
            $workday_start = Carbon::now()->startOfDay()->addHours(8)->addMinutes(30)->format('H:i');

            $lat = $request->input('latitude');
            $long = $request->input('longitude');
            $status = $request->input('status');
            
            $distance = $this->calculateDistance($kantorLatitude, $kantorLongtitude, $lat, $long);
            $allowedRadius = 3;

            if($unit_bisnis->jabatan =='DRIVER'){
                $allowedRadius=9999999;
            }

            if ($distance <= $allowedRadius) {
                $filename = null;
                $absensi = new Absen();
                $absensi->user_id = $nik;
                $absensi->nik = $nik;
                $absensi->tanggal = now()->toDateString();
                $absensi->clock_in = now()->toTimeString();
                $absensi->latitude = $lat;
                $absensi->longtitude = $long;
                $absensi->status = $status;
                if ($request->hasFile('photo')) {
                    $image = $request->file('photo');
                    $filename = time() . '.' . $image->getClientOriginalExtension();

                    // Use Laravel's store method to handle file uploads
                    $path = $image->storeAs('images/absen', $filename, 'public');
                }
                $absensi->photo = $filename;
                $absensi->project_backup = $project_id;
                $absensi->save();
                return response()->json(['message' => 'Clockin success, Happy Working Day!']);
            } else {
                return response()->json(['message' => 'Clockin Rejected, Outside Radius!']);
            }
        } catch (\Exception $e) {

            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while processing the request.',
                'error_details' => $e->getMessage(),
            ], 500);
        }
    }

    public function clockout_backup(Request $request)
    {
        try {
            $token = $request->bearerToken();
            $user = Auth::guard('api')->user();
            $nik = $user->employee_code;
            $unit_bisnis = Employee::where('nik',$nik)->first();

            $lat2 = $request->input('latitude_out');
            $long2 = $request->input('longitude_out');
            
            $currentDate = now()->format('Y-m-d');
            $yesterday = Carbon::yesterday();
            $today = now()->toDateString();

            // Clockout Shift Malam
            if (strcasecmp($unit_bisnis->unit_bisnis, 'Kas') == 0 && strcasecmp($unit_bisnis->organisasi, 'FRONTLINE OFFICER') == 0) {
                
                $scheduleKasYesterday = Schedule::where('employee', $nik)
                    ->whereDate('tanggal', $yesterday)
                    ->first();

                if ($scheduleKasYesterday->shift === 'ML'){
                    $absensiml = Absen::where('nik', $nik)
                                ->whereDate('tanggal', $yesterday)
                                ->orderBy('clock_in', 'desc')
                                ->first();

                    if ($absensiml) {
                        $absensiml->clock_out = now()->format('H:i');
                        $absensiml->latitude_out = $lat2;
                        $absensiml->longtitude_out = $long2;
                        $absensiml->save();
        
                        return response()->json([
                            'status' => 'success',
                            'message' => 'Clockout success! Selamat Beristirahat!',
                        ], 200);
                    } else {
                        return response()->json([
                            'status' => 'error',
                            'message' => 'No clock-in record found for today.',
                        ], 404);
                    }
                }else{
                    $absensi = Absen::where('nik', $nik)
                        ->whereDate('tanggal', $currentDate)
                        ->orderBy('clock_in', 'desc')
                        ->first();

                    if ($absensi) {
                        $absensi->clock_out = now()->format('H:i');
                        $absensi->latitude_out = $lat2;
                        $absensi->longtitude_out = $long2;
                        $absensi->save();

                        return response()->json([
                            'status' => 'success',
                            'message' => 'Clockout success! Selamat Beristirahat!',
                        ], 200);
                    } else {
                        return response()->json([
                            'status' => 'error',
                            'message' => 'No clock-in record found for today.',
                        ], 404);
                    }
                }
                
            }

            $absensi = AbsenBackup::where('nik', $nik)
                ->whereDate('tanggal', $currentDate)
                ->orderBy('clock_in', 'desc')
                ->first();

            if ($absensi) {
                $absensi->clock_out = now()->format('H:i');
                $absensi->latitude_out = $lat2;
                $absensi->longtitude_out = $long2;
                $absensi->save();

                return response()->json([
                    'status' => 'success',
                    'message' => 'Clockout success! Selamat Beristirahat!',
                ], 200);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'No clock-in record found for today.',
                ], 404);
            }
        } catch (\Exception $e) {
            // You may want to customize this based on your logging setup

            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while processing the request.',
            ], 500);
        }
    }


    public function payslipuser(Request $request)
    {
        try {
            // Retrieve the token from the request
            $token = $request->bearerToken();

            // Authenticate the user based on the token
            $user = Auth::guard('api')->user();
            if (!empty($user)) {
                $employeeCode = $user->employee_code;

                // Ensure employeeCode is not null before accessing the database
                if ($employeeCode) {

                    $dataKaryawan = Employee::where('nik', $employeeCode)->first();

                    if ($dataKaryawan) {
                        $karyawan = json_decode($dataKaryawan, true);

                        if (strtolower($karyawan['organisasi']) === 'management leaders') {
                            $payslips = Payrol::where('employee_code', $employeeCode)
                                ->where('payslip_status', 'Published')
                                ->get();
                        } else {
                            $unit_bisnis = $dataKaryawan->unit_bisnis;
                            if ($unit_bisnis == 'Kas') {
                                $payslips = Payroll::where('employee_code', $employeeCode)
                                            ->where('payslip_status', 'Published')
                                            ->get();
                            }elseif($unit_bisnis == 'Run'){
                                $payslips = PayrolUrbanica::where('employee_code', $employeeCode)
                                            ->where('payslip_status', 'Published')
                                            ->get();
                            } else {
                                $payslips = Payrollns::where('employee_code', $employeeCode)
                                            ->where('payslip_status', 'Published')
                                            ->get();
                            }
                        }

                        // Modify the response to return JSON data
                        $payslipsData = $payslips->toArray();


                        return response()->json([
                            'payslips' => $payslips->items(),
                            'current_page' => $payslips->currentPage(),
                            'per_page' => $payslips->perPage(),
                            'total' => $payslips->total(),
                        ]);
                    } else {
                        return response()->json(['error' => 'Data karyawan tidak ditemukan.'], 404);
                    }
                } else {
                    return response()->json(['error' => 'Properti "employee_code" tidak ditemukan pada pengguna.'], 400);
                }
            } else {
                return response()->json(['error' => 'Pengguna tidak terotentikasi.'], 401);
            }
        } catch (\Exception $e) {
            // Handle general errors
            return response()->json(['error' => 'Terjadi kesalahan.'], 500);
        }
    }

    // Details Payslip
    public function PayslipDetails(Request $request, $id)
    {
        $token = $request->bearerToken();

        // Authenticate the user based on the token
        $user = Auth::guard('api')->user();
        $organisasi = Employee::where('nik', $user->employee_code)
            ->select('organisasi', 'unit_bisnis')
            ->first();

        try {
            // Determine the correct model to use based on the unit_bisnis and organisasi
            if (strtolower($organisasi->unit_bisnis) === 'kas') {
                $payslip = (strtolower($organisasi->organisasi) === 'management leaders') ? Payrol::findOrFail($id) : Payroll::findOrFail($id);
            }elseif(strtolower($organisasi->unit_bisnis) === 'run'){
                $payslip = (strtolower($organisasi->organisasi) === 'Frontline Officer') ? Payrol::findOrFail($id) : PayrolUrbanica::findOrFail($id);
            } else {
                $payslip = (strtolower($organisasi->organisasi) === 'management leaders') ? Payrol::findOrFail($id) : Payrollns::findOrFail($id);
            }
            
            // Decode the JSON fields
            $allowances = json_decode($payslip->allowances, true);
            $deductions = json_decode($payslip->deductions, true);

            if (strtolower($organisasi->unit_bisnis) === 'run') {
                // Transform allowances
                $transformedAllowances = [];
                foreach ($allowances as $key => $value) {
                    // Exclude "Total overtime hours" and "Total absence"
                    if (!in_array($key, ['total_overtime_hours', 'total_absence'])) {
                        $transformedAllowances[] = [
                            "name" => ucfirst(str_replace('_', ' ', $key)),
                            "amount" => $value
                        ];
                    }
                }
    
                // Transform deductions
                $transformedDeductions = [];
                foreach ($deductions as $key => $value) {
                    $transformedDeductions[] = [
                        "name" => ucfirst(str_replace('_', ' ', $key)),
                        "amount" => $value
                    ];
                }
    
                // Update the payslip object with the transformed data
                $payslip->allowances = json_encode($transformedAllowances);
                $payslip->deductions = json_encode($transformedDeductions);
    
                // Add additional fields
                $payslip->net_salary = $payslip->thp;
                $payslip->unit_bisnis = 'CHAMPOIL';
                unset($payslip->periode);
            } else {
                // For other organizations, replace component IDs with names
                $allowances = $this->replaceComponentIdsWithNames($allowances, 'allowance');
                $deductions = $this->replaceComponentIdsWithNames($deductions, 'deduction');
    
                // Update the payslip object
                $payslip->allowances = json_encode($allowances);
                $payslip->deductions = json_encode($deductions);
            }

            // Return the payslip data as JSON
            return response()->json(['data' => $payslip], 200);
        } catch (ModelNotFoundException $e) {
            // Handle case when the entity is not found
            return response()->json(['error' => 'Data payslip tidak ditemukan.'], 404);
        } catch (\Exception $e) {
            // Handle general errors
            return response()->json(['error' => 'Terjadi kesalahan.'], 500);
        }
    }

    private function replaceComponentIdsWithNames($components, $type)
    {
        $componentIds = array_keys($components['data']);
        $componentDetails = Component::whereIn('id', $componentIds)->get();

        // Create a map of ID to name for easy lookup
        $componentMap = [];
        foreach ($componentDetails as $detail) {
            $componentMap[$detail->id] = $detail->name;
        }

        $newComponents = [];
        foreach ($components['data'] as $id => $values) {
            $componentName = $componentMap[$id] ?? 'komponen tidak ditemukan';
            $newComponents[] = [
                'name' => $componentName,
                'amount' => $values[0]  // Assuming only one value per component
            ];
        }

        // If the type is allowance or deduction, add total amount
        if ($type === 'allowance' && isset($components['total_allowance'])) {
            $newComponents[] = [
                'name' => 'total_allowance',
                'amount' => $components['total_allowance']
            ];
        }
        if ($type === 'deduction' && isset($components['total_deduction'])) {
            $newComponents[] = [
                'name' => 'total_deduction',
                'amount' => $components['total_deduction']
            ];
        }

        return $newComponents;
    }
    

    // Get Employee Details
    public function getEmployeeByNik($nik)
    {
        try {
            $employee = Employee::where('nik', $nik)->first();

            if ($employee) {
                // Return employee data in JSON format
                $employee->agama = ucfirst(strtolower($employee->agama));
                return response()->json(['data' => $employee], 200);
            } else {
                // Handle the case when the employee is not found
                return response()->json(['error' => 'Employee not found.'], 404);
            }
        } catch (\Exception $e) {
            // Handle general errors
            return response()->json(['error' => 'An error occurred.'], 500);
        }
    }

    // My Profile
    public function getMyProfile($nik)
    {
        try {
            $employee = Employee::where('nik', $nik)->with('payrolinfo')->first();

            if (!$employee) {
                return response()->json(['error' => 'Employee not found.'], 404);
            }

            $employee->id = (string) $employee->id;
            $employee->agama = ucfirst(strtolower($employee->agama));

            $nikdata = $employee->nik;

            $today = now();
            $startDate = $today->day >= 21 ? $today->copy()->day(20) : $today->copy()->subMonth()->day(21);
            $endDate = $today->day >= 21 ? $today->copy()->addMonth()->day(20) : $today->copy()->day(20);

            // Hitung jumlah hari kerja tanpa absensi (termasuk akhir pekan)
            $totalWorkingDays = $startDate->diffInWeekdays($endDate) + 1;

            // Fetch attendance data for the current month
            $attendanceData = DB::table('absens')
                ->select(
                    DB::raw('CAST(id AS CHAR) as id'), // Mengubah id menjadi string
                    'user_id',
                    'nik',
                    'tanggal',
                    'project',
                    'project_backup',
                    'clock_in',
                    'clock_out',
                    'latitude',
                    'longtitude',
                    'latitude_out',
                    'longtitude_out',
                    'status',
                    'created_at',
                    'updated_at',
                    'photo'
                )
                ->where('nik', $nikdata)
                ->whereBetween('tanggal', [$startDate, $endDate])
                ->orderBy('tanggal', 'asc')
                ->get();

            // Hitung jumlah hari dengan absensi
            $daysWithAttendance = count($attendanceData);

            // Hitung jumlah hari tanpa absensi
            $daysWithoutAttendance = $totalWorkingDays - $daysWithAttendance;

            // No ClockOut
            $daysWithClockInNoClockOut = 0;

            foreach ($attendanceData as $absendata) {
                if (!empty($absendata->clock_in) && empty($absendata->clock_out)) {
                    $daysWithClockInNoClockOut++;
                }
            }

            // Sakit
            $sakit = 0;

            foreach ($attendanceData as $absendata) {
                if ($absendata->status == 'Sakit') {
                    $sakit++;
                }
            }

            $izin = 0;
            $leaveTotal = 0;

            foreach ($attendanceData as $absendata) {
                if ($absendata->status == 'Izin') {
                    $izin++;
                }
            }

            // Request Absen
            $requestAbsen = RequestAbsen::where('employee', $nikdata)
                ->whereBetween('tanggal', [$startDate, $endDate])
                ->get();

            // Count Request
            $countRequest = RequestAbsen::where('employee', $nikdata)
                ->whereBetween('tanggal', [$startDate, $endDate])
                ->count();

            $leaveTotal = $sakit + $izin;

            $profileData = [
                'employee' => $employee,
                'attendanceData' => $attendanceData,
                'daysWithoutAttendance' => $daysWithoutAttendance,
                'daysWithClockInNoClockOut' => $daysWithClockInNoClockOut,
                'daysWithAttendance' => $daysWithAttendance,
                'sakit' => $sakit,
                'requestAbsen' => $requestAbsen,
                'countRequest' => $countRequest,
                'izin' => $izin,
                'leaveTotal' => $leaveTotal,
            ];

            return response()->json(['data' => $profileData], 200);
        } catch (\Exception $e) {
            // Handle general errors
            return response()->json(['error' => 'An error occurred.'], 500);
        }
    }

    // History Request
    public function HistoryDataRequest(Request $request)
    {
        try {

            // Retrieve the token from the request
            $token = $request->bearerToken();

            // Validate token
            if (!$token) {
                return response()->json(['error' => 'Token tidak valid.'], 401);
            }

            // Authenticate the user based on the token
            $user = Auth::guard('api')->user();

            // Check if user is authenticated
            if (!$user) {
                return response()->json(['error' => 'Pengguna tidak terotentikasi.'], 401);
            }

            $employeeCode = $user->employee_code;
            $historyData = RequestAbsen::where('employee', $employeeCode)
                ->orderBy('created_at', 'desc') // Mengurutkan berdasarkan tanggal dibuat secara descending
                ->take(5) // Mengambil hanya 5 data terbaru
                ->get();

            return response()->json([
                'employeeCode' => $employeeCode,
                'historyData' => $historyData,
            ], 200);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Terjadi kesalahan.'], 500);
        }
    }


    // Request Absen
    public function submitAbsenceRequest(Request $request)
    {
        try {
            $request->validate([
                'tanggal' => 'required',
                'employee' => 'required',
                'status' => 'required',
                'alasan' => 'required',
            ]);

            
            $tanggal = date('Y-m-d', strtotime($request->input('tanggal')));
            $employee = $request->input('employee');
            $cek_absen = Absen::where('nik', $employee)
                ->whereDate('tanggal', $tanggal)
                ->first();
            if ($cek_absen) {
                return response()->json([
                    'message' => 'Anda sudah melakukan absen pada tanggal ' . date('d F Y', strtotime($tanggal)) . '. Anda tidak bisa mengajukan request absen pada tanggal tersebut.'
                ], 200);
            }

            // Cek apakah ada request absen yang sudah ada dengan status Pending atau Approve
            $existingRequest = RequestAbsen::where('employee', $employee)
                ->where('tanggal', $tanggal)
                ->whereIn('aprrove_status', ['Pending', 'Approved'])
                ->exists();

            if ($existingRequest) {
                return response()->json([
                    'message' => 'Anda sudah memiliki pengajuan absen untuk tanggal ' . date('d F Y', strtotime($tanggal)) . ' dengan status Pending atau Approve. Anda hanya bisa mengajukan ulang jika statusnya Ditolak.'
                ], 200);
            }

            $randomNumber = mt_rand(100000, 999999);

            $pengajuan = new RequestAbsen();
            $pengajuan->unik_code = $randomNumber;
            $pengajuan->tanggal = $request->input('tanggal');
            $pengajuan->employee = $request->input('employee');
            $pengajuan->clock_in = $request->input('clock_in');
            $pengajuan->clock_out = $request->input('clock_out');
            $pengajuan->status = $request->input('status');
            $pengajuan->alasan = $request->input('alasan');
            $pengajuan->jam_lembur = $request->input('jam_lembur');
            $pengajuan->aprrove_status = 'Pending';
            
            $check_schedule = Schedule::where('employee',$request->input('employee'))->where('tanggal',date('Y-m-d',strtotime($request->input('tanggal'))))->first();
            if($check_schedule == "OFF"){
                return response()->json(['message' => 'Anda Tidak Bisa Mengajukan Schedule Karena Pada Tanggal '.date('d F Y',strtotime($request->input('tanggal'))).' Schedule Off'], 200);
            }
            if ($request->hasFile('dokumen')) { 
                $file = $request->file('dokumen');

                // Mendapatkan ekstensi file
                $extension = $file->getClientOriginalExtension();

                // Mengecek apakah file adalah PDF atau JPG
                if ($extension !== 'pdf' && $extension !== 'jpg') {
                    return response()->json(['error' => 'Hanya file PDF dan JPG yang diizinkan.'], 400);
                }

                // Jika file adalah PDF atau JPG maka simpan
                $path = $file->store('public/files');
                $pengajuan->dokumen = $path;
            }

            $pengajuan->save();
            $token = $request->bearerToken();

            // Authenticate the user based on the token
            $user = Auth::guard('api')->user();
            $organisasi = Employee::where('nik',$user->employee_code)
                        ->select('organisasi','unit_bisnis')
                        ->first();
        
            if ($organisasi->unit_bisnis === 'CHAMPOIL' ||  $organisasi->unit_bisnis === 'RUN' || $organisasi->unit_bisnis === 'Run' ||  $organisasi->unit_bisnis === 'KAS' ||  $organisasi->unit_bisnis === 'Kas') {
                $slackChannel = Slack::where('channel', 'Request')->where('company',strtoupper($organisasi->unit_bisnis))->first();
                $slackWebhookUrl = $slackChannel->url;
                $today = now()->toDateString();

                $employeeData = Employee::where('nik', $pengajuan->employee)->first();
                $data = [
                    'text' => "Attendence Request From {$employeeData->nama}",
                    'attachments' => [
                        [
                            'fields' => [
                                [
                                    'title' => 'Tanggal',
                                    'value' => $pengajuan->tanggal,
                                    'short' => true,
                                ],
                                [
                                    'title' => 'Alasan',
                                    'value' => $pengajuan->alasan,
                                    'short' => true,
                                ],
                                [
                                    'title' => 'Untuk Approval Silahkan Cek di Aplikasi Truest',
                                    'short' => true,
                                ]
                            ],
                        ],
                    ],
                    
                ];

                $data_string = json_encode($data);

                $ch = curl_init($slackWebhookUrl);
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, [
                    'Content-Type: application/json',
                    'Content-Length: ' . strlen($data_string),
                ]);

                $result = curl_exec($ch);

                if ($result === false) {
                    // Penanganan kesalahan jika Curl gagal
                    $error = curl_error($ch);
                    // Handle the error here
                    return redirect()->back()->with('error', 'Terjadi kesalahan saat mengirim data ke Slack: ' . $error);
                }

                $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

                if ($httpCode !== 200) {
                    // Penanganan kesalahan jika Slack merespons selain status 200 OK
                    // Handle the error here
                    return redirect()->back()->with('error', 'Terjadi kesalahan saat mengirim data ke Slack. Kode status: ' . $httpCode);
                }

                curl_close($ch);

                return response()->json(['message' => 'Pengajuan berhasil diajukan'], 201);
            }
        
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Handle validation errors
            return response()->json(['error' => $e->validator->errors()], 400);
        } catch (\Exception $e) {
            // Handle other exceptions
            return response()->json(['error' => 'Terjadi kesalahan.'. $e], 500);
        }
    }

    // Log Absen
    public function MyLogsAbsen(Request $request)
    {
        if (Auth::guard('api')->check()) {
            $user = Auth::guard('api')->user();
            $nik = $user->employee_code;
            $unit_bisnis = Employee::where('nik',$nik)->first();
            $lastUpdateProfile = $unit_bisnis->updated_at;

            if ($user->id) {
                $hariini = now()->format('Y-m-d');

                // Update Profile
                $lastUpdateProfile = $unit_bisnis->updated_at;
                $needsUpdate = false;

                if ($lastUpdateProfile && Carbon::parse($lastUpdateProfile)->lt(Carbon::now()->subMonths(3))) {
                    $needsUpdate = true;
                }

                $lastAbsensi = Absen::where('tanggal',$hariini)
                ->where('nik', $user->employee_code)
                ->first();

                $lastAbsensiBackup = AbsenBackup::where('tanggal', $hariini)
                ->where('nik', $user->employee_code)
                ->first();

                $activeSchedule = false;

                // Cek jadwal utama hari ini
                $schedule = Schedule::where('employee', $user->employee_code)
                    ->whereDate('tanggal', $hariini)
                    ->first();

                // Cek apakah ada jadwal backup untuk hari ini
                $scheduleBackup = ScheduleBackup::where('employee', $user->employee_code)
                    ->whereDate('tanggal', $hariini)
                    ->first();

                // Jika ada schedule backup, kita anggap override > aktifkan yang backup
                if ($scheduleBackup) {
                    $activeSchedule = true;
                    $schedule = $scheduleBackup; 
                }


                // Get Data Karyawan
                $userId = $user->id;

                // Get Log Absensi
                $logs = Absen::where('user_id', $user->employee_code)
                    ->whereDate('tanggal', $hariini)
                    ->get();

                // Logs Backup
                $logsBackup = AbsenBackup::where('user_id', $user->employee_code)
                ->whereDate('tanggal', $hariini)
                ->get();
                    
                $today = now();
                $yesterday = Carbon::yesterday();
                $startOfMonth = $today->day >= 21 ? $today->copy()->day(20) : $today->copy()->subMonth()->day(21);
                $endOfMonth = $today->day >= 21 ? $today->copy()->addMonth()->day(20) : $today->copy()->day(20);

                $bulan = $request->input('bulan');
                // Get logs for the month
                if ($bulan) {
                    $logsmonths = Absen::where('user_id', $user->employee_code)
                        ->whereMonth('tanggal', '=', date('m', strtotime($bulan)))
                        ->whereYear('tanggal', '=', date('Y', strtotime($bulan)))
                        ->whereBetween('tanggal', [$startOfMonth, $endOfMonth])
                        ->orderBy('tanggal', 'desc') 
                        ->get();
                } else {
                    $logsmonths = Absen::where('user_id', $user->employee_code)
                        ->whereBetween('tanggal', [$startOfMonth, $endOfMonth])
                        ->orderBy('tanggal', 'desc') 
                        ->get();
                }

                $alreadyClockIn = false;
                $alreadyClockOut = false;
                $isSameDay = false;
                $isBackup = false;
                $alreadyClockInBackup = false;
                $alreadyClockOutBackup = false;
                $isSameDayBackup = false;

                if ($lastAbsensi) {
                    if ($lastAbsensi->clock_in && !$lastAbsensi->clock_out) {
                        $alreadyClockIn = true;
                    } elseif ($lastAbsensi->clock_in && $lastAbsensi->clock_out) {
                        $alreadyClockOut = true;
                        $lastClockOut = Carbon::parse($lastAbsensi->clock_out);
                        $today = Carbon::today();
                        $isSameDay = $lastClockOut->isSameDay($today);
                    }
                }

                if($lastAbsensiBackup){
                    if ($lastAbsensiBackup->clock_in && !$lastAbsensiBackup->clock_out) {
                        $alreadyClockInBackup = true;
                    } elseif ($lastAbsensiBackup->clock_in && $lastAbsensiBackup->clock_out) {
                        $alreadyClockOutBackup = true;
                        $lastClockOutBackup = Carbon::parse($lastAbsensiBackup->clock_out);
                        $today = Carbon::today();
                        $isSameDayBackup = $lastClockOutBackup->isSameDay($today);
                    }
                }

                // Cek jika unit bisnis adalah Kasir dan Organisasi adalah FRONTLINE OFFICER
                if (strcasecmp($unit_bisnis->unit_bisnis, 'Kas') == 0 && strcasecmp($unit_bisnis->organisasi, 'FRONTLINE OFFICER') == 0) {
                    
                    // Ambil jadwal shift malam kemarin (tanggal 3)
                    $scheduleKasYesterday = Schedule::where('employee', $nik)
                        ->whereDate('tanggal', $yesterday) // $yesterday = tanggal 3
                        ->first();

                    if ($scheduleKasYesterday && strcasecmp($scheduleKasYesterday->shift, 'ML') == 0) {
                        // Cek absensi shift malam kemarin (tanggal 3)
                        $logsYesterday = Absen::where('user_id', $user->employee_code)
                            ->whereDate('tanggal', $yesterday) // Ambil log tanggal 3
                            ->get();

                        if ($logsYesterday->isNotEmpty()) {
                            $lastLogYesterday = $logsYesterday->last();
                            $now = now();
                            $isAfter10AM = $now->hour >= 10; 

                            // Jika user sudah clock-in kemarin (tanggal 3) 
                            if ($lastLogYesterday->clock_in !== null) {
                                // Cek apakah user sudah clock-out atau belum
                                $alreadyClockIn = true;
                                $alreadyClockOut = ($lastLogYesterday->clock_out !== null);
                                $logs = $logsYesterday;
                            }
                        }

                        // Cek shift malam hari ini (tanggal 4)
                        $scheduleKasToday = Schedule::where('employee', $nik)
                            ->whereDate('tanggal', Carbon::today())
                            ->first();

                        $logsToday = Absen::where('user_id', $user->employee_code)
                            ->whereDate('tanggal', Carbon::today())
                            ->get();
                            

                        if ($scheduleKasToday && strcasecmp($scheduleKasToday->shift, 'ML') == 0) {
                            $now = now();
                            $isAfter10AM = $now->hour >= 10;

                            // Jika sudah lewat jam 10 pagi, reset tombol Clock-In
                            if ($isAfter10AM) {
                                $alreadyClockIn = false;
                                $alreadyClockOut = false;
                                $logs = collect(); // Kosongkan log hari ini agar tidak muncul
                            }
                        }

                        // Jika sudah ada log absensi hari ini, set tombol ke clock-out
                        if ($logsToday->isNotEmpty()) {
                            $lastLogToday = $logsToday->last();

                            if ($lastLogToday->clock_in !== null) {
                                $alreadyClockIn = true;
                                $alreadyClockOut = ($lastLogToday->clock_out !== null);
                                $logs = $logsToday; // Prioritaskan log hari ini
                            }
                        }
                    }
                }

                // Greating
                date_default_timezone_set('Asia/Jakarta'); // Set timezone sesuai dengan lokasi Anda
                $hour = date('H'); // Ambil jam saat ini

                $totalDaysInMonth = $startOfMonth->diffInDays($endOfMonth);
                
                // Calculate the number of weekend days in the current month
                $weekendDays = 0;
                $currentDate = Carbon::now()->startOfMonth();
                $endOfMonth = Carbon::now()->endOfMonth();

                while ($currentDate <= $endOfMonth) {
                    if ($currentDate->isWeekend()) {
                        $weekendDays++;
                    }
                    $currentDate->addDay();
                }
                
                // Calculate the total weekdays in the current month (excluding weekends)
                $weekdaysInMonth = $totalDaysInMonth - $weekendDays;
                
                // Calculate the number of logs in the current month
                $logsCount = $logsmonths->count();
                
                // Calculate the number of weekdays with no logs
                $daysWithNoLogs = $weekdaysInMonth - $logsCount;

                $bulanSelected = $bulan ? date('F', strtotime($bulan)) : date('F');
                return response()->json([
                    // 'isUpdatedata' => $needsUpdate,
                    'isUpdatedata'=>false,
                    'isBackupSchedule' => $activeSchedule,
                    'alreadyClockIn' => $alreadyClockIn,
                    'alreadyClockOut' => $alreadyClockOut,
                    'isSameDay' => $isSameDay,
                    'alreadyClockInBackup' => $alreadyClockInBackup,
                    'alreadyClockOutBackup' => $alreadyClockOutBackup,
                    'isSameDayBackup' => $isSameDayBackup,
                    'logs' => $logs,
                    'logsBackup' => $logsBackup,
                    'logsmonths' => $logsmonths,
                    'daysWithNoLogs' => $daysWithNoLogs,
                    'bulanSelected' => $bulanSelected,
                    
                ], 200);
            } else {
                return response()->json(['error' => 'Unauthorized'], 401);
            }
        } else {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    }

    // My Schedule
    public function myschedule(Request $request)
    {
        // Retrieve the token from the request
        $token = $request->bearerToken();
        // Authenticate the user based on the token
        $user = Auth::guard('api')->user();

        try {
            if (!$user) {
                return response()->json(['error' => 'Pengguna tidak terotentikasi.'], 401);
            } else {
                $employeeCode = $user->employee_code;
                $currentDate = Carbon::now();
                $nextMonth = $currentDate->addMonth();
                $bulan = $nextMonth->format('F-Y');

                $today = now();
                $startOfMonth = $today->day >= 21 ? $today->copy()->day(20) : $today->copy()->subMonth()->day(21);
                $endOfMonth = $today->day >= 21 ? $today->copy()->addMonth()->day(20) : $today->copy()->day(20);
                
                $mySchedule = Schedule::with('project:id,name') // Eager loading
                        ->where('employee', $employeeCode)
                        ->whereDate('tanggal', '>=', $startOfMonth)
                        ->whereDate('tanggal', '<=', $endOfMonth)
                        ->select('project', 'tanggal', 'shift')
                        ->get();

                return response()->json(['data' => $mySchedule], 200);
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Handle validation errors
            return response()->json(['error' => $e->validator->errors()], 400);
        } catch (\Exception $e) {
            // Handle other exceptions
            return response()->json(['error' => 'Terjadi kesalahan.' . $e], 500);
        }
    }

    public function BackupSchedule(Request $request) 
    {
        // Retrieve the token from the request
        $token = $request->bearerToken();
        // Authenticate the user based on the token
        $user = Auth::guard('api')->user();

        try {
            if (!$user) {
                return response()->json(['error' => 'Pengguna tidak terotentikasi.'], 401);
            } else {
                $employeeCode = $user->employee_code;
                $bulan = now()->format('F-Y');

                $today = now();
                $startOfMonth = $today->day >= 21 ? $today->copy()->day(20) : $today->copy()->subMonth()->day(21);
                $endOfMonth = $today->day >= 21 ? $today->copy()->addMonth()->day(20) : $today->copy()->day(20);
                
                $mySchedule = ScheduleBackup::with('project:id,name')
                    ->where('employee', $employeeCode)
                    ->whereDate('tanggal', '>=', $startOfMonth)
                    ->whereDate('tanggal', '<=', $endOfMonth)
                    ->select('project', 'tanggal', 'shift')
                    ->get();

                return response()->json(['data' => $mySchedule], 200);
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Handle validation errors
            return response()->json(['error' => $e->validator->errors()], 400);
        } catch (\Exception $e) {
            // Handle other exceptions
            return response()->json(['error' => 'Terjadi kesalahan.' . $e], 500);
        }
    }

    public function TypeAttendenceRequest(Request $request)
    {
        try {
            // Get the token from the Authorization header
            $token = $request->bearerToken();
            // Check if the token is valid
            $user = Auth::guard('api')->user();

            if ($user) {
                $code = $user->employee_code;
                $company = Employee::where('nik', $code)->first();
                $check_schedule = Schedule::where('employee',$code)->where('tanggal',date('Y-m-d'))->first();
                // Ensure the "employee_code" property exists in the user object
                if ($company) {
                    $requestType = RequestType::where('company', $company->unit_bisnis)->get();

                    if($company->unit_bisnis == "Kas" && $check_schedule->shift == "OFF"){
                        $requestType = RequestType::where('company', $company->unit_bisnis)->whereIn('code',['PA','L'])->get();
                    }

                    return response()->json(['dataRequest' => $requestType], 200);
                } else {
                    return response()->json(['error' => 'Data Request tidak ditemukan.'], 404);
                }
            } else {
                return response()->json(['error' => 'Token tidak valid atau pengguna tidak terautentikasi.'], 401);
            }
        } catch (\Exception $e) {
            // Handle general errors
            return response()->json(['error' => 'Terjadi kesalahan.'], 500);
        }
    }

    // List Request Absen
    public function AbsenRequest(Request $request)
    {
        try {
            // Retrieve the token from the request
            $token = $request->bearerToken();
            // Authenticate the user based on the token
            $user = Auth::guard('api')->user();

            $employeeCode = $user->name;
            $unitBisnis = Employee::where('nik', $employeeCode)->value('unit_bisnis');
            $company = Employee::where('nik', $employeeCode)->first();

            if($company->organisasi == 'Frontline Officer' || $company->organisasi =='FRONTLINE OFFICER'){
                $get_project = Schedule::where('employee',$employeeCode)->first();
                $request_absen = RequestAbsen::join('karyawan', 'karyawan.nik', '=', 'requests_attendence.employee')
                                            ->where('karyawan.unit_bisnis', $company->unit_bisnis)
                                            ->whereDate('requests_attendence.created_at','>','2024-06-20')
                                            ->where('requests_attendence.aprrove_status','Pending')
                                            ->select('requests_attendence.*','karyawan.nama','karyawan.unit_bisnis')
                                            ->orderBy('requests_attendence.tanggal', 'desc')
                                            ->get();
                $requestAbsen=[];
                if($request_absen){
                    foreach($request_absen as $row){
                        $query = Schedule::whereDate('schedules.tanggal','>','2024-06-20');
                                if(!empty($get_project)){
                                    $query->where('project',$get_project->project);
                                }
                        $cek=$query->where('employee',$row->employee)->count();
                        if($cek > 0){
                            $requestAbsen[]=$row;
                        }
                    }
                    
                }
            }else{
                $requestAbsen = RequestAbsen::join('karyawan', 'karyawan.nik', '=', 'requests_attendence.employee')
                                   ->where('karyawan.unit_bisnis', $company->unit_bisnis)
                                   ->where('requests_attendence.aprrove_status', 'Pending')
                                   ->select('requests_attendence.*','karyawan.nama','karyawan.unit_bisnis')
                                   ->orderBy('requests_attendence.tanggal', 'desc')
                                   ->limit(50)
                                   ->get();
            }

            // $requestAbsen = RequestAbsen::join('karyawan', 'requests_attendence.employee', '=', 'karyawan.nik')
            //     ->where('requests_attendence.aprrove_status', 'Pending')
            //     ->where('karyawan.unit_bisnis', $unitBisnis)
            //     ->select('requests_attendence.*', 'karyawan.nama', 'karyawan.unit_bisnis')
            //     ->get();

            return response()->json(['dataRequest' => $requestAbsen], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error updating request: ' . $e->getMessage()], 500);
        }
    }

    // Approve Request Absen
    public function updateStatusSetuju(Request $request, $id)
    {
        try {
            // Retrieve the token from the request
            $token = $request->bearerToken();
            // Authenticate the user based on the token
            $user = Auth::guard('api')->user();

            $employeeCode = $user->name;

            $requestabsen = RequestAbsen::where('id', $id)->firstOrFail();

            if ($requestabsen->aprrove_status !== 'Approved') {
                $requestabsen->aprrove_status = 'Approved';
                $requestabsen->aprroved_by = $employeeCode;
                $requestabsen->save();

                $dataKaryawanRequest = $requestabsen->employee;
                // Simpan Kedalam Table Absen
                $absen = new Absen();
                $absen->user_id = $dataKaryawanRequest;
                $absen->nik = $dataKaryawanRequest;
                $absen->tanggal = $requestabsen->tanggal;

                if ($requestabsen->status == 'F') {
                    $absen->clock_in = $requestabsen->clock_in;
                    $absen->clock_out = $requestabsen->clock_out;
                } else {
                    $absen->clock_in = null;
                    $absen->clock_out = null;
                }

                $absen->latitude = null;
                $absen->longtitude = null;
                $absen->status = $requestabsen->status;
                $absen->save();
            }

            return response()->json(['message' => 'Attendance Request has been Updated']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error updating request: ' . $e->getMessage()], 500);
        }
    }

    // Log Absen Backup
    public function LogBackup(Request $request)
    {
        if (Auth::guard('api')->check()) {
            $user = Auth::guard('api')->user();
            $nik = $user->employee_code;
            $unit_bisnis = Employee::where('nik',$nik)->first();
            
            if ($user->id) {
                $hariini = now()->format('Y-m-d');
                $lastAbsensi = Absen::where('tanggal',$hariini)
                ->where('nik', $user->employee_code)
                ->first();
                // Get Data Karyawan
                $userId = $user->id;
                $today = now();
                // Get Log Absensi
                $logs = Absen::where('nik', $user->employee_code)
                    ->whereDate('tanggal', $hariini)
                    ->get();
                
                $project_backup = ScheduleBackup::where('employee', $nik)
                ->whereDate('tanggal', $today)
                ->pluck('project');

                $yesterday = Carbon::yesterday();
                $startOfMonth = $today->day >= 21 ? $today->copy()->day(20) : $today->copy()->subMonth()->day(21);
                $endOfMonth = $today->day >= 21 ? $today->copy()->addMonth()->day(20) : $today->copy()->day(20);

                $bulan = $request->input('bulan');
                // Get logs for the month
                if($bulan) {
                    $logsmonths = Absen::where('nik', $user->employee_code)
                        ->whereMonth('tanggal', '=', date('m', strtotime($bulan)))
                        ->whereYear('tanggal', '=', date('Y', strtotime($bulan)))
                        ->whereBetween('tanggal', [$startOfMonth, $endOfMonth])
                        ->orderBy('tanggal')
                        ->get();
                } else {
                    $logsmonths = Absen::where('nik', $user->employee_code)
                        ->whereBetween('tanggal', [$startOfMonth, $endOfMonth])
                        ->orderBy('tanggal')
                        ->get();
                }

                // Remove Absen Button
                $alreadyClockIn = false;
                $alreadyClockOut = false;
                $isSameDay = false;

                if ($lastAbsensi) {
                    if ($lastAbsensi->clock_in && !$lastAbsensi->clock_out) {
                        $alreadyClockIn = true;
                    } elseif ($lastAbsensi->clock_in && $lastAbsensi->clock_out) {
                        $alreadyClockOut = true;
                        $lastClockOut = Carbon::parse($lastAbsensi->clock_out);
                        $today = Carbon::today();
                        $isSameDay = $lastClockOut->isSameDay($today);
                    }
                }

                // Cek jika unit bisnis adalah Kasir dan Organisasi adalah FRONTLINE OFFICER
                if (strcasecmp($unit_bisnis->unit_bisnis, 'Kas') == 0 && strcasecmp($unit_bisnis->organisasi, 'FRONTLINE OFFICER') == 0) {
                    
                    // Ambil jadwal shift malam kemarin (tanggal 3)
                    $scheduleKasYesterday = ScheduleBackup::where('employee', $nik)
                        ->whereDate('tanggal', $yesterday) // $yesterday = tanggal 3
                        ->first();

                    if ($scheduleKasYesterday && strcasecmp($scheduleKasYesterday->shift, 'ML') == 0) {
                        // Cek absensi shift malam kemarin (tanggal 3)
                        $logsYesterday = Absen::where('user_id', $user->employee_code)
                            ->whereDate('tanggal', $yesterday) // Ambil log tanggal 3
                            ->get();

                        if ($logsYesterday->isNotEmpty()) {
                            $lastLogYesterday = $logsYesterday->last();
                            $now = now();
                            $isAfter10AM = $now->hour >= 10; 

                            // Jika user sudah clock-in kemarin (tanggal 3) 
                            if ($lastLogYesterday->clock_in !== null) {
                                // Cek apakah user sudah clock-out atau belum
                                $alreadyClockIn = true;
                                $alreadyClockOut = ($lastLogYesterday->clock_out !== null);
                                $logs = $logsYesterday;
                            }
                        }

                        // Cek shift malam hari ini (tanggal 4)
                        $scheduleKasToday = ScheduleBackup::where('employee', $nik)
                            ->whereDate('tanggal', Carbon::today())
                            ->first();

                        if ($scheduleKasToday && strcasecmp($scheduleKasToday->shift, 'ML') == 0) {
                            $now = now();
                            $isAfter10AM = $now->hour >= 10;

                            // Jika sudah lewat jam 10 pagi, reset tombol Clock-In
                            if ($isAfter10AM) {
                                $alreadyClockIn = false;
                                $alreadyClockOut = false;
                                $logs = collect(); // Kosongkan log hari ini agar tidak muncul
                            }
                        }
                    }
                }
                

                // Greating
                date_default_timezone_set('Asia/Jakarta'); // Set timezone sesuai dengan lokasi Anda
                $hour = date('H'); // Ambil jam saat ini

                $totalDaysInMonth = $startOfMonth->diffInDays($endOfMonth);
                
                // Calculate the number of weekend days in the current month
                $weekendDays = 0;
                $currentDate = Carbon::now()->startOfMonth();
                $endOfMonth = Carbon::now()->endOfMonth();

                while ($currentDate <= $endOfMonth) {
                    if ($currentDate->isWeekend()) {
                        $weekendDays++;
                    }
                    $currentDate->addDay();
                }
                $startDate = now()->day >= 21 ? now()->copy()->day(20) : now()->copy()->subMonth()->day(21);
                $endDate = now()->day >= 21 ? now()->copy()->addMonth()->day(20) : now()->copy()->day(20);

                $BackupPeriodeLog = Absen::where('nik', $user->employee_code)
                            ->whereBetween('tanggal', [$startDate, $endDate])
                            ->get();
                // Calculate the total weekdays in the current month (excluding weekends)
                $weekdaysInMonth = $totalDaysInMonth - $weekendDays;
                
                // Calculate the number of logs in the current month
                $logsCount = $logsmonths->count();
                
                // Calculate the number of weekdays with no logs
                $daysWithNoLogs = $weekdaysInMonth - $logsCount;

                $bulanSelected = $bulan ? date('F', strtotime($bulan)) : date('F');
                return response()->json([
                    'alreadyClockIn' => $alreadyClockIn,
                    'alreadyClockOut' => $alreadyClockOut,
                    'isSameDay' => $isSameDay,
                    'logs' => $logs,
                    'project_backup' => $project_backup,
                    'logsmonths' => $logsmonths,
                    'daysWithNoLogs' => $daysWithNoLogs,
                    'bulanSelected' => $bulanSelected,
                    'LogAbsenBackupPeriode' => $BackupPeriodeLog
                ], 200);
            } else {
                return response()->json(['error' => 'Unauthorized'], 401);
            }
        } else {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    }



    public function updateStatusReject(Request $request, $id)
    {
        try {

            $token = $request->bearerToken();
            // Authenticate the user based on the token
            $user = Auth::guard('api')->user();
            $employeeCode = $user->employee_code;

            $requestAbsen = RequestAbsen::where('id', $id)->firstOrFail();

            if ($requestAbsen->aprrove_status !== 'Reject') {
                $requestAbsen->aprrove_status = 'Reject';
                $requestAbsen->aprroved_by = $employeeCode;
                $requestAbsen->save();
            }

            return response()->json(['message' => 'Data Pengajuan Berhasil Diupdate.'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error updating request: ' . $e->getMessage()], 500);
        }
    }

    public function downloadFilesAttendence(Request $request, $id)
    {
        try {
            $token = $request->bearerToken();
            // Authenticate the user based on the token
            $user = Auth::guard('api')->user();
            $requestabsen = RequestAbsen::where('unik_code', $id)->firstOrFail();
        
            $file_path = storage_path('app/' . $requestabsen->dokumen);
        
            return response()->download($file_path);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            // Handle the case when no data is found
            return response()->json(['error' => 'Data not found.'], 404);
        } catch (\Exception $e) {
            // Handle other exceptions
            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }
}