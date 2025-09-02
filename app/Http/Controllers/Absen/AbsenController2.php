<?php

namespace App\Http\Controllers\Absen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Exports\AttendenceExport;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Log;

// Model
use App\Absen;
use App\Employee;
use App\ModelCG\Schedule;
use App\ModelCG\ScheduleBackup;
use App\ModelCG\Project;
use App\ModelCG\Datamaster\ProjectShift;
use App\Absen\RequestAbsen;
use App\Backup\AbsenBackup;
use App\User;
use App\Organisasi\Organisasi;
use App\Company\CompanyModel;
use App\Company\CompanySetting;
use App\Company\ScheduleModel;
use App\Company\ShiftModel;
use App\Company\WorkLocation;

// helper
use App\Helpers\CompanySettingHelper;


class AbsenController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $nik = $user->employee_code;

        $employee = Employee::where('nik', $nik)->first();
        $org = $employee->unit_bisnis;
        $companyId = CompanyModel::where('company_name', $org)->value('id');
        $settings = CompanySetting::where('company_id', $companyId)->pluck('value', 'key');

        if($org == 'Kas'){
            $result=[];

            $projectIds = json_decode($user->project_id, true); // ubah string JSON jadi array
            if (!$projectIds || !is_array($projectIds)) {
                $project_list = Project::where('company', 'KAS')->get();
            }else{
                $project_list = Project::whereIn('id', $projectIds)->get();
            }
            $month = $request->input('month', now()->month);
            $year = $request->input('year', now()->year);
            $workLocationId = $request->input('work_location_id');
            $selectedOrg = $request->input('org');
            $organisasiId = $request->input('organisasi_id');
            $useSchedule = CompanySettingHelper::get($companyId, 'use_schedule', false);
    
            $startDate = null;
            $endDate = null;
    
            // Cek apakah ada pengaturan cutoff_start dan cutoff_end
            if (isset($settings['cutoff_start']) && isset($settings['cutoff_end'])) {
                $cutoffStartDay = (int) $settings['cutoff_start'];
                $cutoffEndDay = (int) $settings['cutoff_end'];
    
                // Tentukan tanggal cutoff mulai berdasarkan bulan dan tahun yang diberikan
                $startDate = \Carbon\Carbon::createFromDate($year, $month, $cutoffStartDay)->startOfDay();
    
                // Tentukan tanggal cutoff akhir berdasarkan bulan berikutnya dan tanggal yang diberikan
                $endDate = $startDate->copy()->addMonth()->day($cutoffEndDay)->endOfDay();
    
                // Jika tanggal akhir bulan berikutnya tidak valid (misalnya 31 Februari), sesuaikan
                if ($endDate->day != $cutoffEndDay) {
                    $endDate->day = $endDate->daysInMonth;
                }
            } else {
                // Jika tidak ada pengaturan cutoff_start dan cutoff_end, fallback ke bulan yang dimaksud
                $startDate = Carbon::create($year, $month, 1);  // fallback ke tanggal 1 bulan jika cutoff_start tidak ada
                $endDate = $startDate->copy()->endOfMonth();  // fallback ke akhir bulan jika cutoff_end tidak ada
            }
            
            $dates = [];
            while ($startDate <= $endDate) {
                $dates[] = $startDate->format('Y-m-d');
                $startDate->addDay();
            }


            $result['useMultilocation'] = CompanySettingHelper::get($companyId, 'use_multilocation');

            $result['org'] = $org;
            $result['project_list'] = $project_list;
            $result['dates'] = $dates;

            $schedule = Schedule::join('karyawan', 'karyawan.nik', '=', 'schedules.employee')
                ->whereBetween('tanggal', [$startDate, $endDate])
                ->get()
                ->groupBy('tanggal'); // Group data by date for horizontal looping

            $result['schedule'] = $schedule;
            dd($result['schedule']);
            
            
            return view('pages.absen.kas.index', $result);
        }else{
            $month = $request->input('month', now()->month);
            $year = $request->input('year', now()->year);
            $workLocationId = $request->input('work_location_id');
            $selectedOrg = $request->input('org');
            $organisasiId = $request->input('organisasi_id');
            $useSchedule = CompanySettingHelper::get($companyId, 'use_schedule', false);
    
            $startDate = null;
            $endDate = null;
    
            // Cek apakah ada pengaturan cutoff_start dan cutoff_end
            if (isset($settings['cutoff_start']) && isset($settings['cutoff_end'])) {
                $cutoffStartDay = (int) $settings['cutoff_start'];
                $cutoffEndDay = (int) $settings['cutoff_end'];
    
                // Tentukan tanggal cutoff mulai berdasarkan bulan dan tahun yang diberikan
                $startDate = \Carbon\Carbon::createFromDate($year, $month, $cutoffStartDay)->startOfDay();
    
                // Tentukan tanggal cutoff akhir berdasarkan bulan berikutnya dan tanggal yang diberikan
                $endDate = $startDate->copy()->addMonth()->day($cutoffEndDay)->endOfDay();
    
                // Jika tanggal akhir bulan berikutnya tidak valid (misalnya 31 Februari), sesuaikan
                if ($endDate->day != $cutoffEndDay) {
                    $endDate->day = $endDate->daysInMonth;
                }
            } else {
                // Jika tidak ada pengaturan cutoff_start dan cutoff_end, fallback ke bulan yang dimaksud
                $startDate = Carbon::create($year, $month, 1);  // fallback ke tanggal 1 bulan jika cutoff_start tidak ada
                $endDate = $startDate->copy()->endOfMonth();  // fallback ke akhir bulan jika cutoff_end tidak ada
            }
            
            $dates = [];
            while ($startDate <= $endDate) {
                $dates[] = $startDate->format('Y-m-d');
                $startDate->addDay();
            }
    
            $scheduleEmployeeIds = collect();
            $scheduleEmployeeNiks = collect();
            if ($useSchedule) {
                if (strtoupper($org) === 'KAS') {
                    $scheduleEmployeeNiks = Schedule::query()
                        ->whereBetween('tanggal', [reset($dates), end($dates)])
                        ->when($workLocationId, function ($q) use ($workLocationId) {
                            $q->where('project', $workLocationId);
                        })
                        ->pluck('employee')
                        ->unique();
                } else {
                    $scheduleEmployeeIds = ScheduleModel::query()
                        ->whereBetween('work_date', [reset($dates), end($dates)])
                        ->when($workLocationId, function ($q) use ($workLocationId) {
                            $q->where('work_location_id', $workLocationId);
                        })
                        ->pluck('employee_id')
                        ->unique();
                }
            }
    
            $employees = Employee::query()
                ->where('resign_status', 0)
                ->when($selectedOrg, function ($q) use ($selectedOrg) {
                    $q->where('unit_bisnis', $selectedOrg);
                })
                ->when($organisasiId, function ($q) use ($organisasiId) {
                    $q->where('organisasi', $organisasiId);
                })
                ->when($workLocationId && $useSchedule && strtoupper($org) !== 'KAS', function ($q) use ($scheduleEmployeeIds) {
                    $q->whereIn('id', $scheduleEmployeeIds);
                })
                ->when($workLocationId && $useSchedule && strtoupper($org) === 'KAS', function ($q) use ($scheduleEmployeeNiks) {
                    $q->whereIn('nik', $scheduleEmployeeNiks);
                })
                ->where('unit_bisnis', $org)
                ->get();
    
            $niks = $employees->pluck('nik');
    
            $absens = Absen::whereBetween('tanggal', [reset($dates), end($dates)])
                ->whereIn('nik', $niks)
                ->get()
                ->groupBy('nik');
            
            if (strtoupper($org) === 'KAS') {
                $schedules = Schedule::whereBetween('tanggal', [reset($dates), end($dates)])
                    ->whereIn('employee', $employees->pluck('nik'))
                    ->get()
                    ->groupBy(function($s) {
                        return $s->employee . '-' . $s->tanggal; // Sesuaikan dengan field yang tepat
                    });
    
                    \Log::info('Grouped Schedules:', $schedules->toArray());
            }else {
                $schedules = ScheduleModel::whereBetween('work_date', [reset($dates), end($dates)])
                    ->whereIn('employee_id', $employees->pluck('id'))
                    ->get()
                    ->groupBy(fn($s) => $s->employee_id . '-' . $s->work_date);
            }
    
            if (strtoupper($org) === 'KAS') {
                $shifts = ProjectShift::all();
            } else {
                $shifts = ShiftModel::where('company_id', $companyId)->get()->keyBy('id');
            }
            $projectIds = json_decode($user->project_id, true); // ubah string JSON jadi array
            if (!$projectIds || !is_array($projectIds)) {
                $project_list = Project::where('company', 'KAS')->get();
            }else{
                $project_list = Project::whereIn('id', $projectIds)->get();
            }
            
            
            $useMultilocation = CompanySettingHelper::get($companyId, 'use_multilocation');
            
            
            return view('pages.absen.index', compact('employees', 'dates', 'absens', 'schedules', 'shifts', 'useMultilocation', 'org','companyId','project_list'));
        }

        
    }

   

    public function indexbackup()
    {
        $code = Auth::user()->employee_code;
        $company = Employee::where('nik', $code)->first();

        // Menghitung tanggal mulai dan tanggal akhir berdasarkan aturan bisnis
        $today = now();
        $startDate = $today->day >= 21 ? $today->copy()->day(20) : $today->copy()->subMonth()->day(21);
        $endDate = $today->day >= 21 ? $today->copy()->addMonth()->day(20) : $today->copy()->day(20);

        // Query untuk data absensi
        $query = DB::table('users')
            ->join('karyawan', 'karyawan.nik', '=', 'users.employee_code')
            ->leftJoin('absen_backup', function ($join) use ($startDate, $endDate) {
                $join->on('absen_backup.nik', '=', 'users.employee_code')
                    ->whereBetween('absen_backup.tanggal', [$startDate, $endDate])
                    ->whereNotNull('absen_backup.project');
            })
            ->where('karyawan.unit_bisnis', $company->unit_bisnis);

        // Mengambil data absensi dari database
        $data1 = $query->select('users.*', 'absen_backup.*')
            ->orderBy('users.name')
            ->get();

        // Mengirim data ke tampilan
        return view('pages.absen.backup.databackup', compact('data1', 'endDate', 'startDate'));
    }

    public function filterByOrganization(Request $request)
    {
        $selectedOrganization = $request->input('organization', '');
        $project = $request->input('project', '');
        $periode = $request->input('periode', '');
    
        return redirect()->route('absen.index', [
            'organization' => $selectedOrganization,
            'project' => $project,
            'periode' => $periode
        ]);
    }


    public function exportAttendence(Request $request)
    {

        $user = Auth::user();
        $nik = $user->employee_code;

        $employee = Employee::where('nik', $nik)->first();
        $org = $employee->unit_bisnis;
        $companyId = CompanyModel::where('company_name', $org)->value('id');

        $validatedData = $request->validate([
            'selected_month' => 'required|string',
        ]);

        // Ambil bulan dan tahun dari input (format: YYYY-MM)
        $selectedMonthWithYear = date('Y') . '-' . date('m', strtotime($request->selected_month));
        $selectedDate = Carbon::createFromFormat('Y-m', $selectedMonthWithYear);
        $year = $selectedDate->year;
        $month = $selectedDate->month;
        $organisasi = $request->input('organisasi');

        try {
            // Ambil NIK user yang login
            $loggedInUserNik = auth()->user()->employee_code;
            $employee = Employee::where('nik', $loggedInUserNik)->first();
            $unitBisnis = $employee->unit_bisnis;

            // Ambil settingan cutoff dari company_settings
            $settings = CompanySetting::where('company_id', $companyId)->pluck('value', 'key'); // pastikan ini adalah helper yg ambil settingan sesuai company user

            $startDate = null;
            $endDate = null;

            if (isset($settings['cutoff_start']) && isset($settings['cutoff_end'])) {
                $cutoffStartDay = (int) $settings['cutoff_start'];
                $cutoffEndDay = (int) $settings['cutoff_end'];

                // Tentukan tanggal cutoff mulai dari bulan yang dipilih
                $startDate = Carbon::create($year, $month, $cutoffStartDay)->startOfDay();

                // Tanggal akhir: bulan berikutnya, tanggal cutoff end
                $endDate = $startDate->copy()->addMonth()->day($cutoffEndDay)->endOfDay();

                // Handle jika tanggal cutoff_end melebihi jumlah hari di bulan
                if ($endDate->day != $cutoffEndDay) {
                    $endDate->day = $endDate->daysInMonth;
                }
            } else {
                // Fallback jika tidak ada setting cutoff
                $startDate = Carbon::create($year, $month, 1)->startOfDay();
                $endDate = $startDate->copy()->endOfMonth()->endOfDay();
            }

            // Buat file export Excel
            $export = new AttendenceExport($unitBisnis, $loggedInUserNik, $startDate, $endDate, $organisasi);
            return Excel::download($export, 'attendence_export_' . strtolower($startDate->format('F_Y')) . '.xlsx');

        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat mengunduh file Excel: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    public function absenBackup()
    {
        $userId = Auth::id();
        $EmployeeCode = Auth::user()->employee_code;
        $hariini = now()->format('Y-m-d');

        $datakaryawan = Employee::join('users', 'karyawan.nik', '=', 'users.employee_code')
                    ->where('users.employee_code', $userId)
                    ->select('karyawan.*')
                    ->get();

        $databackup = ScheduleBackup::where('employee', $EmployeeCode)->get();
        return view('pages.absen.backup.index', compact('datakaryawan','databackup'));
    }

    // Absen Masuk

    public function clockin(Request $request)
    {
        try {
            $user = Auth::user();
            $nik = $user->employee_code;
            
            $unit_bisnis = Employee::where('nik', $nik)->first();

            $employeeId = $unit_bisnis->id;
            $companyId = CompanyModel::where('company_name', $unit_bisnis->unit_bisnis)->value('id');

            $today = Carbon::now()->format('Y-m-d');
            $nowTime = now()->format('H:i');
            $todayName = now()->locale('id')->isoFormat('dddd');

            // Ambil setting dari company_setting
            $useRadius = CompanySettingHelper::get($companyId, 'use_radius');
            $radiusValue = CompanySettingHelper::get($companyId, 'radius_value', 5);
            $gpsCoordinatesRaw = CompanySettingHelper::get($companyId, 'gps_coordinates');
            $gpsCoordinates = is_string($gpsCoordinatesRaw) ? json_decode($gpsCoordinatesRaw, true) : $gpsCoordinatesRaw;
            $useShift = CompanySettingHelper::get($companyId, 'use_shift');
            $useSchedule = CompanySettingHelper::get($companyId, 'use_schedule');
            $defaultInTime = CompanySettingHelper::get($companyId, 'default_in_time');
            $defaultOutTime = CompanySettingHelper::get($companyId, 'default_out_time');
            $lateTolerance = CompanySettingHelper::get($companyId, 'grace_period', 0);
            $workdays = CompanySettingHelper::get($companyId, 'workdays', []);
            $useMultiLocation = CompanySettingHelper::get($companyId, 'use_multilocation');

            // Validasi hari kerja
            if (!in_array($todayName, $workdays)) {
                return redirect()->back()->with('error', 'Clockin Rejected, Hari ini bukan hari kerja.');
            }

            // Validasi duplikat absen
            if (Absen::where('nik', $nik)->whereDate('tanggal', $today)->exists()) {
                return back()->with('error', 'Clockin Rejected, Anda sudah absen hari ini.');
            }

            // Ambil data lokasi user dari request
            $lat = $request->input('latitude');
            $long = $request->input('longitude');

            // Default lokasi kantor            
            $kantorLat = $gpsCoordinates['latitude'] ?? null;
            $kantorLong = $gpsCoordinates['longitude'] ?? null;
            $projectId = null;
            $shift = null;
            $workLocationId = null;
            $workLocationLat = null;
            $workLocationLong = null;

            // Kode khusus untuk company "KAS" tetap dipertahankan
            if (strtoupper($unit_bisnis->unit_bisnis) === 'KAS') {
                if ($useSchedule) {
                    $schedule = Schedule::where('employee', $nik)->whereDate('tanggal', $today)->first();
                    if ($schedule) {
                        $projectId = $schedule->project;
                        $shift = $schedule->shift;

                        if ($projectId) {
                            $project = Project::find($projectId);
                            $kantorLat = $project->latitude;
                            $kantorLong = $project->longtitude;
                        }
                    }
                }
            } else {
                // Untuk company umum: ambil schedule dan lokasi kerja
                if ($useSchedule) {
                    $schedule = ScheduleModel::where('employee_id', $employeeId)->whereDate('work_date', $today)->first();
                    if ($schedule) {
                        $shift = $schedule->shift_id;
                        $projectId = $schedule->work_location_id;

                        if ($useMultiLocation && $projectId) {
                            $projectId = WorkLocation::find($projectId);
                            if ($projectId) {
                                $workLocationLat = $projectId->latitude;
                                $workLocationLong = $projectId->longitude;
                            }
                        }
                    }
                }

                // Ganti lokasi jika multi lokasi aktif
                if ($useMultiLocation && $workLocationLat && $workLocationLong) {
                    $kantorLat = $workLocationLat;
                    $kantorLong = $workLocationLong;
                }
            }

            // Radius khusus untuk DRIVER
            $allowedRadius = strtoupper($unit_bisnis->jabatan) === 'DRIVER' ? 9999999 : $radiusValue;

            // Hitung jarak dan validasi radius
            if ($useRadius) {
                $distance = $this->calculateDistance($kantorLat, $kantorLong, $lat, $long);
                if ($distance > $allowedRadius) {
                    return back()->with('error', 'Clockin Rejected, Anda diluar radius.');
                }
            }

            // Validasi jam masuk jika tidak pakai shift/schedule
            if (!$useShift && !$useSchedule && $defaultInTime) {
                $toleransiMasuk = Carbon::createFromFormat('H:i', $defaultInTime)->addMinutes($lateTolerance)->format('H:i');
                if ($nowTime > $toleransiMasuk) {
                    return back()->with('error', 'Clockin Rejected, Melebihi waktu masuk dan toleransi.');
                }
            }

            // Validasi jam shift jika menggunakan shift atau schedule
            if (($useShift || $useSchedule) && $shift) {
                $shiftModel = ShiftModel::where('id', $shift)
                    ->where('company_id', $companyId)
                    ->when($useMultiLocation && $workLocationId, function ($q) use ($workLocationId) {
                        return $q->where('work_location_id', $workLocationId);
                    })->first();

                if ($shiftModel) {
                    
                    // Validasi Schedule Off
                    if ($shiftModel->is_off == 1) {
                        return back()->with('error', 'Anda tidak bisa absen karena hari ini adalah hari libur.');
                    }

                    $startShift = Carbon::parse($shiftModel->start_time);
                    $endShift = Carbon::parse($shiftModel->end_time);
                    $currentTime = now();

                    // Tangani shift malam
                    if ($endShift->lt($startShift)) {
                        $endShift->addDay();
                        if ($currentTime->lt($startShift)) {
                            $currentTime->addDay();
                        }
                    }

                    if ($currentTime->lt($startShift)) {
                        return back()->with('error', 'Clockin Rejected, Shift belum dimulai.');
                    }
                } else {
                    return back()->with('error', 'Shift tidak ditemukan.');
                }
            }

            // Simpan absen
            $absen = new Absen();
            $absen->user_id = $nik;
            $absen->nik = $nik;
            $absen->tanggal = $today;
            $absen->clock_in = $nowTime;
            $absen->latitude = $lat;
            $absen->longtitude = $long;
            $absen->status = $request->input('status');
            $absen->project = $projectId->id;
            $absen->absen_type = "web";

            if ($request->hasFile('photo')) {
                $image = $request->file('photo');
                $filename = time() . '.' . $image->getClientOriginalExtension();
                $destinationPath = public_path('/images/absen');
                $image->move($destinationPath, $filename);
                $absen->photo = $filename;
            }

            $absen->save();

            return back()->with('success', 'Clockin success, Happy Working Day!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }


    // Absen Balik
    public function clockout(Request $request)
    {
        try {
            $nik = Auth::user()->employee_code;
            $lat2 = $request->input('latitude_out');
            $long2 = $request->input('longitude_out');
            $currentDate = now()->format('Y-m-d');

            // Ambil unit bisnis & company_id
            $employee = Employee::where('nik', $nik)->first();
            $companyId = CompanyModel::where('company_name', $employee->unit_bisnis)->value('id');

            // Ambil jam pulang dari setting
            $defaultOutTime = CompanySettingHelper::get($companyId, 'default_out_time');

            // Cek clockout tidak boleh sebelum default_out_time
            if ($defaultOutTime) {
                $jamPulang = \Carbon\Carbon::createFromFormat('H:i', $defaultOutTime);
                $nowTime = now();

                if ($nowTime->lt($jamPulang)) {
                    return redirect()->back()->with('error', 'Belum waktunya pulang, Jam Pulang ('. $defaultOutTime .') WIB');
                }
            }

            // Ambil absen hari ini
            $absensi = Absen::where('nik', $nik)
                ->whereDate('tanggal', $currentDate)
                ->orderBy('clock_in', 'desc')
                ->first();

            if ($absensi) {
                $absensi->clock_out = now()->format('H:i');
                $absensi->latitude_out = $lat2;
                $absensi->longtitude_out = $long2;
                $absensi->save();

                return redirect()->back()->with('success', 'Clockout success!, Selamat Beristirahat!');
            }
        } catch (\Exception $e) {
            // Tampilkan pesan kesalahan atau log pengecualian
            dd($e->getMessage());
        }
    }

    // Absen Backup masuk

    public function clockinbackup(Request $request)
    {   
        $user = Auth::user();
        $nik = Auth::user()->employee_code;
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

        $allowedRadius = 5;

        if($unit_bisnis->jabatan =='DRIVER'){
            $allowedRadius=9999999;
        }

        if ($distance <= $allowedRadius) {
            $absensi = new absen();
            $absensi->user_id = $nik;
            $absensi->nik = $nik;
            $absensi->tanggal = now()->toDateString();
            $absensi->clock_in = now()->toTimeString();
            $absensi->latitude = $lat;
            $absensi->longtitude = $long;
            $absensi->status = $status;
            $absensi->project_backup = $project_id;
            $absensi->absen_type = "web";
            $absensi->save();
            return redirect()->back()->with('success', 'Clockin success, Happy Working Day!');
        } else {
            return redirect()->back()->with('error', 'Anda Diluar Radius Absen!');
        }
    }

    // Absen Backup Balik

    public function clockoutbackup(Request $request)
    {
        $nik = Auth::user()->employee_code;
        $lat2 = $request->input('latitude_out');
        $long2 = $request->input('longitude_out');
        $currentDate = now()->format('Y-m-d');

        $absensi = Absen::where('nik', $nik)
            ->whereDate('tanggal', $currentDate)
            ->orderBy('clock_in', 'desc')
            ->first();
        
        if ($absensi) {
            $absensi->clock_out = Carbon::now()->toTimeString();
            $absensi->latitude_out = $lat2;
            $absensi->longtitude_out = $long2;
            $absensi->save();

            return redirect()->back()->with('success', 'Clockout success!, Selamat Beristirahat!');
        }

        return redirect()->back()->with('error', 'No clockin record found.');
    }

    // Hitung Jarak Radius

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

    public function show($nik)
    {
        $absensi = Absen::where('nik', $nik)->get();
        
        return view('pages.absen.show', compact('absensi'));
    }

    public function detailsAbsen($nik)
    {
        try {
            $today = now();
            $startDate = $today->day >= 21 ? $today->copy()->day(20) : $today->copy()->subMonth()->day(21);
            $endDate = $today->day >= 21 ? $today->copy()->addMonth()->day(20) : $today->copy()->day(20);
            
            // Hitung Total Hari Kerja
            $totalWorkingDays = $startDate->diffInWeekdays($endDate);

            $absensi = Absen::where('nik', $nik)
                ->whereBetween('tanggal', [$startDate, $endDate])
                ->get();

            $ontime = count($absensi);
            $namaKaryawan = Employee::where('nik', $nik)
                ->select('nama','nik')
                ->first();

            // Hitung Tidak Absen
            $daysWithoutAttendance = $totalWorkingDays - $ontime;

            // No ClockOut
            $daysWithClockInNoClockOut = 0;

            foreach ($absensi as $absendata) {
                if (!empty($absendata->clock_in) && empty($absendata->clock_out)) {
                    $daysWithClockInNoClockOut++;
                }
            }

            // Sakit
            $sakit = 0;

            foreach ($absensi as $absendata) {
                if ($absendata->status == 'Sakit') {
                    $sakit++;
                }
            }

            $izin = 0;

            foreach ($absensi as $absendata) {
                if ($absendata->status == 'Izin') {
                    $izin++;
                }
            }

            // Request Absen
            $absensirequest = RequestAbsen::where('employee',$nik)
                            ->whereBetween('tanggal', [$startDate, $endDate])
                            ->get();

            $CountRequest = count($absensirequest);

            return view('pages.absen.show', compact('absensi', 'startDate', 'endDate','namaKaryawan','ontime','daysWithoutAttendance','daysWithClockInNoClockOut','sakit','izin','CountRequest'));
        } catch (\Exception $e) {
            // Handle the exception (e.g., log it, show an error page, etc.)
            return response()->back()->with('error', 'No record found.');
        }
    }

    // Backup Details
    public function detailsAbsenBackup($nik)
    {
        try {
            $today = now();
            $startDate = $today->day >= 21 ? $today->copy()->day(20) : $today->copy()->subMonth()->day(21);
            $endDate = $today->day >= 21 ? $today->copy()->addMonth()->day(20) : $today->copy()->day(20);
            
            // Hitung Total Hari Kerja
            $totalWorkingDays = $startDate->diffInWeekdays($endDate);

            $absensi = AbsenBackup::where('nik', $nik)
                ->whereBetween('tanggal', [$startDate, $endDate])
                ->whereNotNull('project')
                ->get();

            $ontime = count($absensi);
            $namaKaryawan = Employee::where('nik', $nik)
                ->select('nama','nik')
                ->first();

            // Hitung Tidak Absen
            $daysWithoutAttendance = $totalWorkingDays - $ontime;

            // No ClockOut
            $daysWithClockInNoClockOut = 0;

            foreach ($absensi as $absendata) {
                if (!empty($absendata->clock_in) && empty($absendata->clock_out)) {
                    $daysWithClockInNoClockOut++;
                }
            }

            // Sakit
            $sakit = 0;

            foreach ($absensi as $absendata) {
                if ($absendata->status == 'Sakit') {
                    $sakit++;
                }
            }

            $izin = 0;

            foreach ($absensi as $absendata) {
                if ($absendata->status == 'Izin') {
                    $izin++;
                }
            }

            // Request Absen
            $absensirequest = RequestAbsen::where('employee',$nik)
                            ->whereBetween('tanggal', [$startDate, $endDate])
                            ->get();

            $CountRequest = count($absensirequest);

            return view('pages.absen.backup.show', compact('absensi', 'startDate', 'endDate','namaKaryawan','ontime','daysWithoutAttendance','daysWithClockInNoClockOut','sakit','izin','CountRequest'));
        } catch (\Exception $e) {
            // Handle the exception (e.g., log it, show an error page, etc.)
            return response()->back()->with('error', 'No record found.');
        }
    }

    // Delete Absen

    public function deleteAttendance($date, $nik)
    {
        $dataAbsen = Absen::where('tanggal', $date)
        ->where('nik', $nik)
        ->delete();
        return redirect()->back()->with('success', 'Attendance successfully deleted');
    }

    public function deleteAttendanceBackup($date, $nik)
    {
        $dataAbsen = AbsenBackup::where('tanggal', $date)
        ->where('nik', $nik)
        ->delete();
        return redirect()->back()->with('success', 'Attendance successfully deleted');
    }


    public function duplicateAttendance(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $duplicates = Absen::select('absens.nik', 'karyawan.nama', 'absens.tanggal', DB::raw('COUNT(*) as count'))
            ->join('karyawan', 'absens.nik', '=', 'karyawan.nik')
            ->whereBetween('absens.tanggal', [$startDate, $endDate])
            ->groupBy('absens.nik', 'karyawan.nama', 'absens.tanggal')
            ->having('count', '>', 1)
            ->get();

        return view('pages.absen.duplikat', compact('duplicates', 'startDate', 'endDate'));
    }

    public function deleteDuplicate($nik, $tanggal, Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // Find duplicate records for the given nik and date
        $records = Absen::where('nik', $nik)
            ->where('tanggal', $tanggal)
            ->get();

        // Keep one record and delete the others
        if ($records->count() > 1) {
            // Delete all but the first record
            $records->slice(1)->each(function($record) {
                $record->delete();
            });
        }

        // Check if there is an off schedule for the given date and nik
        $offSchedule = Schedule::where('employee', $nik)
            ->where('tanggal', $tanggal)
            ->where('shift', 'OFF')
            ->first();

        if ($offSchedule && $records->isEmpty()) {
            // If there is an off schedule and no absen record left, delete the off schedule
            $offSchedule->delete();
        }

        return redirect()->route('absens.index', ['start_date' => $startDate, 'end_date' => $endDate])->with('success', 'Duplicate records deleted successfully.');
    }


    public function bulkDeleteDuplicates(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $duplicates = $request->input('duplicates');

        if ($duplicates) {
            foreach ($duplicates as $duplicate) {
                list($nik, $tanggal) = explode('|', $duplicate);

                // Find duplicate records for the given nik and date
                $records = Absen::where('nik', $nik)
                    ->where('tanggal', $tanggal)
                    ->get();

                // Keep one record and delete the others
                if ($records->count() > 1) {
                    // Delete all but the first record
                    $records->slice(1)->each(function($record) {
                        $record->delete();
                    });
                }

                // Check if there is an off schedule for the given date and nik
                $offSchedule = Schedule::where('employee', $nik)
                    ->where('tanggal', $tanggal)
                    ->where('shift', 'OFF')
                    ->first();

                if ($offSchedule && $records->isEmpty()) {
                    // If there is an off schedule and no absen record left, delete the off schedule
                    $offSchedule->delete();
                }
            }
        }

        return redirect()->route('absens.index', ['start_date' => $startDate, 'end_date' => $endDate])->with('success', 'Selected duplicate records deleted successfully.');
    }


}
