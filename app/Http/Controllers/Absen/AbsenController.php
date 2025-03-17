<?php

namespace App\Http\Controllers\Absen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Absen;
use App\Employee;
use App\ModelCG\Schedule;
use App\ModelCG\ScheduleBackup;
use App\ModelCG\Project;
use App\ModelCG\Datamaster\ProjectShift;
use App\Absen\RequestAbsen;
use App\Backup\AbsenBackup;
use App\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Exports\AttendenceExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Company\CompanyModel;
use Yajra\DataTables\Facades\DataTables;
use App\Organisasi\Organisasi;
use Illuminate\Support\Facades\Log;
class AbsenController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $code = Auth::user()->employee_code;
        $company = Employee::where('nik', $code)->first();
        $project = Project::all();
        $client_id = Auth::user()->project_id;
        $organisasi = Organisasi::where('company', $company->unit_bisnis)->get();
    
        $today = now();
        $periode = $request->input('periode');
        Log::info('Periode dari request:', ['periode' => $periode]);
        if ($periode) {
            // Parsing periode
            [$startDate, $endDate] = explode(' - ', $periode);
            Log::info('Data hasil explode:', ['start' => $startDate, 'end' => $endDate]);
        } else {
            // Periode default
            $startDate = Carbon::create($today->year, $today->month, 21)->format('Y-m-d');
            $endDate = Carbon::create($today->year, $today->month, 20)->addMonth()->format('Y-m-d');
        }

        Log::info('Periode dari default:', ['start' => $startDate]);
        Log::info('Periode dari default:', ['end' => $endDate]);
    
        // Query absensi
        $query = DB::table('users')
            ->join('karyawan', 'karyawan.nik', '=', 'users.name')
            ->leftJoin('absens', function ($join) use ($startDate, $endDate) {
                $join->on('absens.nik', '=', 'users.name')
                    ->whereBetween('absens.tanggal', [$startDate, $endDate]);
            })
            ->select(
                'users.id as user_id',
                'karyawan.nik',
                'karyawan.nama',
                'karyawan.organisasi',
                'karyawan.unit_bisnis',
                DB::raw("GROUP_CONCAT(absens.tanggal ORDER BY absens.tanggal) as dates"),
                DB::raw("GROUP_CONCAT(absens.clock_in ORDER BY absens.tanggal) as clock_ins"),
                DB::raw("GROUP_CONCAT(absens.clock_out ORDER BY absens.tanggal) as clock_outs")
            )
            ->where('karyawan.unit_bisnis', $company->unit_bisnis)
            ->where('karyawan.resign_status', '0')
            ->groupBy('users.id', 'karyawan.nik', 'karyawan.nama', 'karyawan.organisasi', 'karyawan.unit_bisnis')
            ->orderBy('karyawan.nama');
    
        if ($request->organisasi && $request->organisasi !== 'ALL') {
            $query->where('karyawan.organisasi', $request->organisasi);
        }
    
        if ($request->project && $request->project !== 'ALL') {
            $query->where('absens.project', $request->project);
        }

        if(!empty($client_id)){
            $query->where('absens.project', $client_id);
        }
    
        if ($request->ajax()) {
            return DataTables::of($query)
                ->addIndexColumn()
                ->editColumn('nama', function ($row) {
                    $link = route('absen.details', ['nik' => $row->nik]);
                    return '<a href="' . $link . '">' . htmlspecialchars($row->nama) . '</a>';
                })
                ->addColumn('attendance', function ($row) use ($startDate, $endDate) {
                    $attendanceData = [];
                    $dates = explode(',', $row->dates);
                    $clockIns = explode(',', $row->clock_ins);
                    $clockOuts = explode(',', $row->clock_outs);
    
                    $dateIndexMap = array_flip($dates);
    
                    foreach (CarbonPeriod::create($startDate, $endDate) as $date) {
                        $formattedDate = $date->format('Y-m-d');
                        if (isset($dateIndexMap[$formattedDate])) {
                            $index = $dateIndexMap[$formattedDate];
                            $clockIn = $clockIns[$index] ?? '-';
                            $clockOut = $clockOuts[$index] ?? '-';
                        } else {
                            $clockIn = $clockOut = '-';
                        }
                        $attendanceData['absens_' . $date->format('Ymd')] = [
                            'clock_in' => $clockIn,
                            'clock_out' => $clockOut,
                        ];
                    }
    
                    return $attendanceData;
                })
                ->filter(function ($query) use ($request) {
                    if ($request->has('search') && $request->search['value'] !== '') {
                        $search = strtolower($request->search['value']);
                        $query->where(DB::raw('LOWER(karyawan.nama)'), 'LIKE', "%{$search}%");
                    }
                })
                ->rawColumns(['nama'])
                ->toJson();
        }
    
        // Generate daftar bulan untuk dropdown
        $months = [];
        for ($i = -1; $i < 13; $i++) {
            $start = $today->copy()->startOfYear()->addYear($i >= 12 ? 1 : 0)->addMonths($i % 12)->day(21);
            $end = $start->copy()->addMonth()->day(20);
            $months[$start->format('Y-m-d') . ' - ' . $end->format('Y-m-d')] = $end->format('M Y');
        }

    
        return view('pages.absen.index', compact('endDate', 'startDate', 'months', 'project', 'client_id', 'organisasi'));
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
        $selectedMonth = $request->input('selected_month');

        // Validasi input bulan
        $validatedData = $request->validate([
            'selected_month' => 'required|string', // Validasi jenis data
        ]);

        // Tambahkan tahun ke nilai bulan
        $selectedMonthWithYear = date('Y') . '-' . date('m',strtotime($selectedMonth));

        // Konversi input bulan menjadi objek Carbon
        $selectedDate = Carbon::createFromFormat('Y-m', $selectedMonthWithYear);

        // Dapatkan tahun dan bulan dari input
        $year = $selectedDate->year;
        // $month = $selectedDate->month;

        // Dapatkan tanggal awal dan akhir periode berdasarkan bulan yang dipilih
        // $startDate = Carbon::create($year, $month, 21)->startOfMonth();
        // $endDate = $startDate->copy()->addMonth()->subDay();
        $monthNumber = $selectedMonth;
        $startDate = Carbon::create($year, $monthNumber, 21);
        if ($monthNumber == 12) {
            // Handle December case, where end date should be in the next year
            $endDate = Carbon::create($year + 1, 1, 20);
        } else {
            $endDate = Carbon::create($year, $monthNumber + 1, 20);
        }


        try {
            // Lakukan proses eksportasi seperti sebelumnya
            $loggedInUserNik = auth()->user()->employee_code;
            $company = Employee::where('nik', $loggedInUserNik)->first();
            $unitBisnis = $company->unit_bisnis;

            $export = new AttendenceExport($unitBisnis, $loggedInUserNik, $startDate, $endDate);

            return Excel::download($export, 'attendence_export_' . strtolower($startDate->format('F')) . '.xlsx');
        } catch (\Exception $e) {
            // Tampilkan pesan kesalahan kepada pengguna
            return back()->with('error', 'Terjadi kesalahan saat mengunduh file Excel. Silakan coba lagi.');
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


    public function clockin(Request $request)
    {   
        try {
            $user = Auth::user();
            $nik = Auth::user()->employee_code;
            $unit_bisnis = Employee::where('nik',$nik)->first();

            $today = Carbon::now()->format('Y-m-d');

            $schedulebackup = Schedule::where('employee', $nik)
                ->whereDate('tanggal', $today)
                ->get();

            $project_id = null;
            foreach($schedulebackup as $databackup)
            {
                $project_id = $databackup->project;
                $tanggal_backup = $databackup->tanggal;
                $shift = $databackup->shift;
                $periode = $databackup->periode;
            }
            
            if ($project_id !== null) {
                $dataProject = Project::where('id', $project_id)->first();
                $latitudeProject = $dataProject->latitude;
                $longtitudeProject = $dataProject->longtitude;
            
                $kantorLatitude = $latitudeProject;
                $kantorLongtitude = $longtitudeProject;
                $allowedRadius = 5;
            } else {
                $dataCompany = CompanyModel::where('company_name', $unit_bisnis->unit_bisnis)->first();
            
                $kantorLatitude = $dataCompany->latitude;
                $kantorLongtitude = $dataCompany->longitude;
                $allowedRadius = $dataCompany->radius;
            }

            $shift_fix=0;
            $msg = "";
            if ($unit_bisnis->unit_bisnis == "Kas" || $unit_bisnis->unit_bisnis == "KAS") {
                $cek = ProjectShift::where('shift_code', $shift)
                    ->where('project_id', $project_id)
                    ->get();
            
                if ($cek->isEmpty()) {
                    $shift_fix=0;
                    $msg="Shift tidak ditemukan";
                }
            
                $nowTime = now()->format('H:i'); // Ambil waktu sekarang (jam:menit:detik)

                foreach ($cek as $row) {
                    if ($nowTime >= $row->jam_masuk && $nowTime <= $row->jam_pulang) {
                        $shift_fix=1;
                    }
                }
            
                $shift_fix=0;
                $msg="Anda tidak bisa clock in karena schedule " . $shift . ' (' . $row->jam_masuk . ' sampai ' . $row->jam_pulang . ')';
            }

            if($unit_bisnis->unit_bisnis == "KAS" || $unit_bisnis->unit_bisnis == "Kas" && $shift_fix==0){
                return redirect()->back()->with('error', $msg);
            }
            

            // Fet Data From Device User
            $lat = $request->input('latitude');
            $long = $request->input('longitude');
            $status = $request->input('status');
            
            // Hitung Radius
            $distance = $this->calculateDistance($kantorLatitude, $kantorLongtitude, $lat, $long);

            $existingAbsen = Absen::where('nik', $nik)
                ->whereDate('tanggal', $today)
                ->first();

            if ($existingAbsen) {
                return redirect()->back()->with('error', 'Clockin Rejected, Already Clocked In for Today!');
            }

            if ($distance <= $allowedRadius) {
                // Simpan Data
                $absensi = new absen();
                $absensi->user_id = $nik;
                $absensi->nik = $nik;
                $absensi->tanggal = now()->toDateString();
                $absensi->clock_in = now()->format('H:i');
                $absensi->latitude = $kantorLatitude;
                $absensi->longtitude = $kantorLongtitude;
                $absensi->status = $status;
                if ($request->hasFile('photo')) {
                    $image = $request->file('photo');
                    $filename = time() . '.' . $image->getClientOriginalExtension();
                    $destinationPath = public_path('/images/absen');
                    $image->move($destinationPath, $filename);
                    $absensi->photo = $filename;
                }
                $absensi->save();
                return redirect()->back()->with('success', 'Clockin success, Happy Working Day!');
            } else {
                return redirect()->back()->with('error', 'Clockin Rejected, Anda Diluar Radius');
            }
        } catch (\Exception $e) {
            // Handle the exception here
            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    public function clockinbackup(Request $request)
    {   
        $user = Auth::user();
        $nik = Auth::user()->employee_code;

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
            $absensi->save();
            return redirect()->back()->with('success', 'Clockin success, Happy Working Day!');
        } else {
            return redirect()->back()->with('error', 'Anda Diluar Radius Absen!');
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

    public function clockout(Request $request)
    {
        try {
            $nik = Auth::user()->employee_code;
            $lat2 = $request->input('latitude_out');
            $long2 = $request->input('longitude_out');
            $currentDate = now()->format('Y-m-d');

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

    public function clockoutbackup(Request $request)
    {
        $nik = Auth::user()->employee_code;
        $lat2 = $request->input('latitude_out');
        $long2 = $request->input('longitude_out');
        $currentDate = now()->format('Y-m-d');
        $absensi = Absen::where('nik', $nik)
            ->whereDate('clock_in', $currentDate) // Filter berdasarkan tanggal saat ini
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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
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

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

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
