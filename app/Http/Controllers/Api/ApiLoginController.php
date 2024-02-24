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

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Login Failed!',
            ]);
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
        try {
            $token = $request->bearerToken();
            $user = Auth::guard('api')->user();
            $nik = $user->employee_code;
            $unit_bisnis = Employee::where('nik',$nik)->first();
            $today = now()->toDateString();

            $schedulebackup = Schedule::where('employee', $nik)
                ->whereDate('tanggal', $today)
                ->first();

            if ($schedulebackup) {
                $dataProject = Project::find($schedulebackup->project);
                $kantorLatitude = $dataProject->latitude;
                $kantorLongitude = $dataProject->longtitude;
                $allowedRadius = 5;
            } else {
                $dataCompany = CompanyModel::where('company_name', $unit_bisnis->unit_bisnis)->first();
           
                $kantorLatitude = $dataCompany->latitude;
                $kantorLongitude = $dataCompany->longitude;
                $allowedRadius = $dataCompany->radius;
            }

            if (strcasecmp($unit_bisnis->unit_bisnis, 'Kas') == 0 && strcasecmp($unit_bisnis->organisasi, 'FRONTLINE OFFICER') == 0) {
                $scheduleKas = Schedule::where('employee', $nik)
                    ->whereDate('tanggal', $today)
                    ->first();

                    if (!$scheduleKas) {
                        return response()->json(['message' => 'Clock In Rejected, Schedule not found!']);
                    }elseif ($scheduleKas->shift === 'OFF'){
                        return response()->json(['message' => 'Clock In Rejected, Schedule not found!']);
                    }

            }

            $lat = $request->input('latitude');
            $long = $request->input('longitude');
            $status = $request->input('status');

            $distance = $this->calculateDistance($kantorLatitude, $kantorLongitude, $lat, $long);

            if ($distance <= $allowedRadius) {
                $filename = null;

                if ($request->hasFile('photo')) {
                    $image = $request->file('photo');
                    $filename = time() . '.' . $image->getClientOriginalExtension();

                    // Use Laravel's store method to handle file uploads
                    $path = $image->storeAs('images/absen', $filename, 'public');
                }

                Absen::create([
                    'user_id' => $nik,
                    'nik' => $nik,
                    'tanggal' => now()->toDateString(),
                    'clock_in' => now()->format('H:i'),
                    'latitude' => $lat,
                    'longtitude' => $long,
                    'status' => $status,
                    'photo' => $filename,
                ]);

                return response()->json(['message' => 'Clockin success, Happy Working Day!']);
            } else {
                return response()->json(['message' => 'Clockin Rejected, Outside Radius!']);
            }
        } catch (\Exception $e) {
            // Log the error or handle it appropriately
            return response()->json(['message' => 'An error occurred while processing the request.']);
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

                // $scheduleToday = Schedule::where('employee', $nik)
                //     ->whereDate('tanggal', $today)
                //     ->first();
                    
                // $jamshift = $scheduleToday->shift;
                // $getJam = Shift::where('code', $jamshift)->select('waktu_selesai')->first();
                
                // $currentHour = date('H');
                // $jamshift = $scheduleKas->shift;
                // $getJam = Shift::where('code', $jamshift)->select('waktu_selesai')->first();
                
                // if($currentHour <= $getJam->waktu_selesai){
                //     return response()->json(['message' => 'Clock In Rejected, Waktu Shift Belum Dimulai!']);
                // }else{
                //     $absensi = Absen::where('nik', $nik)
                //         ->whereDate('tanggal', $currentDate)
                //         ->orderBy('clock_in', 'desc')
                //         ->first();

                //     if ($absensi) {
                //         $absensi->clock_out = now()->format('H:i');
                //         $absensi->latitude_out = $lat2;
                //         $absensi->longtitude_out = $long2;
                //         $absensi->save();

                //         return response()->json([
                //             'status' => 'success',
                //             'message' => 'Clockout success! Selamat Beristirahat!',
                //         ], 200);
                //     } else {
                //         return response()->json([
                //             'status' => 'error',
                //             'message' => 'No clock-in record found for today.',
                //         ], 404);
                //     }
                // }

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
        } catch (\Exception $e) {
            // You may want to customize this based on your logging setup

            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while processing the request.',
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

            if ($distance <= $allowedRadius) {
                $filename = null;
                $absensi = new AbsenBackup();
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
                $absensi->project = $project_id;
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
            if ($user) {
                $employeeCode = $user->employee_code;

                // Ensure employeeCode is not null before accessing the database
                if ($employeeCode) {
                    $cacheKey = 'payslips:' . $employeeCode;

                    // Check if data is already in cache
                    $cachedData = Cache::get($cacheKey);
                    if ($cachedData) {
                        return response()->json(['payslips' => $cachedData]);
                    }

                    $dataKaryawan = Employee::where('nik', $employeeCode)->first();

                    if ($dataKaryawan) {
                        $karyawan = json_decode($dataKaryawan, true);

                        if ($karyawan['organisasi'] === 'Management Leaders') {
                            $payslips = Payrol::where('employee_code', $employeeCode)
                                ->where('payslip_status', 'Published')
                                ->get();
                        } else {
                            $unit_bisnis = $dataKaryawan->unit_bisnis;
                            if ($unit_bisnis == 'Kas') {
                                $payslips = Payroll::where('employee_code', $employeeCode)
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

                        // Store data in cache for future requests
                        Cache::put($cacheKey, $payslipsData, 60); // Set expiration time in minutes

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
        $organisasi = Employee::where('nik',$user->employee_code)
                    ->select('organisasi','unit_bisnis')
                    ->first();

        try {
            if ($organisasi->unit_bisnis === 'CHAMPOIL') {
                if($organisasi->organisasi === 'Management Leaders') {
                    $payslip = Payrol::findOrFail($id);
                } else {
                    $payslip = Payrollns::findOrFail($id);
                }
            }else{
                if($organisasi->organisasi === 'Management Leaders') {
                    $payslip = Payrol::findOrFail($id);
                } else {
                    $payslip = Payroll::findOrFail($id);
                }
            }
            // Jika ditemukan, kembalikan data dalam format JSON
            return response()->json(['data' => $payslip], 200);
        } catch (ModelNotFoundException $e) {
            // Tangani kasus ketika entitas tidak ditemukan
            return response()->json(['error' => 'Data payslip tidak ditemukan.'], 404);
        } catch (\Exception $e) {
            // Tangani kesalahan umum
            return response()->json(['error' => 'Terjadi kesalahan.'], 500);
        }
    }

    // Get Employee Details
    public function getEmployeeByNik($nik)
    {
        try {
            $employee = Employee::where('nik', $nik)->first();

            if ($employee) {
                // Return employee data in JSON format
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

            $nikdata = $employee->nik;

            $today = now();
            $startDate = $today->day >= 21 ? $today->copy()->day(21) : $today->copy()->subMonth()->day(21);
            $endDate = $today->day >= 21 ? $today->copy()->addMonth()->day(20) : $today->copy()->day(20);

            // Hitung jumlah hari kerja tanpa absensi (termasuk akhir pekan)
            $totalWorkingDays = $startDate->diffInWeekdays($endDate) + 1;

            // Fetch attendance data for the current month
            $attendanceData = DB::table('absens')
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
            $historyData = RequestAbsen::where('employee', $employeeCode)->paginate(15);

            return response()->json([
                'employeeCode' => $employeeCode,
                'historyData' => $historyData->items(),
                'current_page' => $historyData->currentPage(),
                'per_page' => $historyData->perPage(),
                'total' => $historyData->total(),
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

            $randomNumber = mt_rand(100000, 999999);

            $pengajuan = new RequestAbsen();
            $pengajuan->unik_code = $randomNumber;
            $pengajuan->tanggal = $request->input('tanggal');
            $pengajuan->employee = $request->input('employee');
            $pengajuan->clock_in = $request->input('clock_in');
            $pengajuan->clock_out = $request->input('clock_out');
            $pengajuan->status = $request->input('status');
            $pengajuan->alasan = $request->input('alasan');
            $pengajuan->aprrove_status = 'Pending';

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

            $slackChannel = Slack::where('channel', 'Request')->first();
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
            
            if ($user->id) {
                $hariini = now()->format('Y-m-d');
                $lastAbsensi = Absen::where('tanggal',$hariini)
                ->where('nik', $user->employee_code)
                ->first();
                // Get Data Karyawan
                $userId = $user->id;

                // Get Log Absensi
                $logs = Absen::where('user_id', $user->employee_code)
                    ->whereDate('tanggal', $hariini)
                    ->get();
                    
                $today = now();
                $yesterday = Carbon::yesterday();
                $startOfMonth = $today->day >= 21 ? $today->copy()->day(20) : $today->copy()->subMonth()->day(21);
                $endOfMonth = $today->day >= 21 ? $today->copy()->addMonth()->day(20) : $today->copy()->day(20);

                $bulan = $request->input('bulan');
                // Get logs for the month
                if($bulan) {
                    $logsmonths = Absen::where('user_id', $user->employee_code)
                        ->whereMonth('tanggal', '=', date('m', strtotime($bulan)))
                        ->whereYear('tanggal', '=', date('Y', strtotime($bulan)))
                        ->whereBetween('tanggal', [$startOfMonth, $endOfMonth])
                        ->orderBy('tanggal')
                        ->get();
                } else {
                    $logsmonths = Absen::where('user_id', $user->employee_code)
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

                // Cek Button
                if (strcasecmp($unit_bisnis->unit_bisnis, 'Kas') == 0 && strcasecmp($unit_bisnis->organisasi, 'FRONTLINE OFFICER') == 0) {
                
                    $scheduleKasYesterday = Schedule::where('employee', $nik)
                        ->whereDate('tanggal', $yesterday)
                        ->first();
    
                    if (strcasecmp($scheduleKasYesterday->shift, 'ML') == 0 ){
                        $alreadyClockIn = true;
                        $logs = Absen::where('user_id', $user->employee_code)
                                ->whereDate('tanggal', $yesterday)
                                ->get();

                        if ($logs->isEmpty()) {
                            // Jika belum ada log absen, set tombol ke clock in
                            $alreadyClockIn = false;
                            $alreadyClockOut = false;
                        } else {
                            // Jika sudah ada log absen, cek apakah yang terakhir adalah clock in atau clock out
                            $lastLog = $logs->last();
                    
                            if ($lastLog->clock_out === null) {
                                // Jika yang terakhir adalah clock in, set tombol ke clock out
                                $alreadyClockIn = true;
                                $alreadyClockOut = false;
                            } else {
                                // Jika yang terakhir adalah clock out, set tombol ke clock in
                                $alreadyClockIn = false;
                                $alreadyClockOut = false;
                                
                                $today = Carbon::today();
                                $logs = Absen::where('user_id', $user->employee_code)
                                        ->whereDate('tanggal', $today)
                                        ->get();
                                
                                
                                $logsHarinini = Absen::where('user_id', $user->employee_code)
                                        ->whereDate('tanggal', $today)
                                        ->first();
                                
                                if ($logsHarinini !== null) {
                                    if ($logsHarinini->clock_in !== null) {
                                        $alreadyClockIn = true;
                                        $logs = Absen::where('user_id', $user->employee_code)
                                            ->whereDate('tanggal', $today)
                                            ->get();
                                    } else {
                                        $alreadyClockIn = false;
                                        $alreadyClockOut = false;
                                        $logs = Absen::where('user_id', $user->employee_code)
                                            ->whereDate('tanggal', $today)
                                            ->get();
                                    }
                                } else {
                                    // Handle ketika $logsHarinini adalah null, misalnya memberikan pesan atau tindakan lainnya
                                    $alreadyClockIn = false;
                                    $alreadyClockOut = false;
                                    $logs = collect(); // Menggunakan koleksi kosong untuk menghindari error jika dibutuhkan
                                }
                                
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
                    'alreadyClockIn' => $alreadyClockIn,
                    'alreadyClockOut' => $alreadyClockOut,
                    'isSameDay' => $isSameDay,
                    'logs' => $logs,
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
                
                $mySchedule = Schedule::with('project:id,name') // Eager loading
                    ->where('employee', $employeeCode)
                    ->where('periode', $bulan)
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
                
                $mySchedule = ScheduleBackup::with('project:id,name')
                    ->where('employee', $employeeCode)
                    ->where('periode', $bulan)
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
                // Ensure the "employee_code" property exists in the user object
                if ($company) {
                    $requestType = RequestType::where('company', $company->unit_bisnis)->get();

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

            $requestAbsen = RequestAbsen::join('karyawan', 'requests_attendence.employee', '=', 'karyawan.nik')
                ->where('requests_attendence.aprrove_status', 'Pending')
                ->where('karyawan.unit_bisnis', $unitBisnis)
                ->select('requests_attendence.*', 'karyawan.nama', 'karyawan.unit_bisnis')
                ->get();

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
                $lastAbsensi = AbsenBackup::where('tanggal',$hariini)
                ->where('nik', $user->employee_code)
                ->first();
                // Get Data Karyawan
                $userId = $user->id;

                // Get Log Absensi
                $logs = AbsenBackup::where('nik', $user->employee_code)
                    ->whereDate('tanggal', $hariini)
                    ->get();
                    
                $today = now();
                $yesterday = Carbon::yesterday();
                $startOfMonth = $today->day >= 21 ? $today->copy()->day(20) : $today->copy()->subMonth()->day(21);
                $endOfMonth = $today->day >= 21 ? $today->copy()->addMonth()->day(20) : $today->copy()->day(20);

                $bulan = $request->input('bulan');
                // Get logs for the month
                if($bulan) {
                    $logsmonths = AbsenBackup::where('nik', $user->employee_code)
                        ->whereMonth('tanggal', '=', date('m', strtotime($bulan)))
                        ->whereYear('tanggal', '=', date('Y', strtotime($bulan)))
                        ->whereBetween('tanggal', [$startOfMonth, $endOfMonth])
                        ->orderBy('tanggal')
                        ->get();
                } else {
                    $logsmonths = AbsenBackup::where('nik', $user->employee_code)
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

                // Cek Button
                if (strcasecmp($unit_bisnis->unit_bisnis, 'Kas') == 0 && strcasecmp($unit_bisnis->organisasi, 'FRONTLINE OFFICER') == 0) {
                
                    $scheduleKasYesterday = ScheduleBackup::where('employee', $nik)
                        ->whereDate('tanggal', $yesterday)
                        ->first();
    
                    if (strcasecmp($scheduleKasYesterday->shift, 'ML') == 0 ){
                        $alreadyClockIn = true;
                        $logs = AbsenBackup::where('nik', $user->employee_code)
                                ->whereDate('tanggal', $yesterday)
                                ->get();

                        if ($logs->isEmpty()) {
                            // Jika belum ada log absen, set tombol ke clock in
                            $alreadyClockIn = false;
                            $alreadyClockOut = false;
                        } else {
                            // Jika sudah ada log absen, cek apakah yang terakhir adalah clock in atau clock out
                            $lastLog = $logs->last();
                    
                            if ($lastLog->clock_out === null) {
                                // Jika yang terakhir adalah clock in, set tombol ke clock out
                                $alreadyClockIn = true;
                                $alreadyClockOut = false;
                            } else {
                                // Jika yang terakhir adalah clock out, set tombol ke clock in
                                $alreadyClockIn = false;
                                $alreadyClockOut = false;
                                
                                $today = Carbon::today();
                                $logs = AbsenBackup::where('nik', $user->employee_code)
                                        ->whereDate('tanggal', $today)
                                        ->get();
                                
                                
                                $logsHarinini = AbsenBackup::where('nik', $user->employee_code)
                                        ->whereDate('tanggal', $today)
                                        ->first();
                                
                                if ($logsHarinini !== null) {
                                    if ($logsHarinini->clock_in !== null) {
                                        $alreadyClockIn = true;
                                        $logs = AbsenBackup::where('nik', $user->employee_code)
                                            ->whereDate('tanggal', $today)
                                            ->get();
                                    } else {
                                        $alreadyClockIn = false;
                                        $alreadyClockOut = false;
                                        $logs = AbsenBackup::where('nik', $user->employee_code)
                                            ->whereDate('tanggal', $today)
                                            ->get();
                                    }
                                } else {
                                    // Handle ketika $logsHarinini adalah null, misalnya memberikan pesan atau tindakan lainnya
                                    $alreadyClockIn = false;
                                    $alreadyClockOut = false;
                                    $logs = collect(); // Menggunakan koleksi kosong untuk menghindari error jika dibutuhkan
                                }
                                
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
                    'alreadyClockIn' => $alreadyClockIn,
                    'alreadyClockOut' => $alreadyClockOut,
                    'isSameDay' => $isSameDay,
                    'logs' => $logs,
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