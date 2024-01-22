<?php

namespace App\Http\Controllers\Absen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Absen;
use App\Employee;
use App\ModelCG\Schedule;
use App\ModelCG\ScheduleBackup;
use App\ModelCG\Project;
use App\Absen\RequestAbsen;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Exports\AttendenceExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Company\CompanyModel;

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

        // Mendapatkan data organisasi terpilih (jika ada)
        $selectedOrganization = $request->input('organization');

        // Menghitung tanggal mulai dan tanggal akhir berdasarkan aturan bisnis
        $today = now();
        $startDate = $today->day >= 21 ? $today->copy()->day(20) : $today->copy()->subMonth()->day(21);
        $endDate = $today->day >= 21 ? $today->copy()->addMonth()->day(20) : $today->copy()->day(20);

        // Query untuk data absensi
        $query = DB::table('users')
            ->join('karyawan', 'karyawan.nik', '=', 'users.employee_code')
            ->leftJoin('absens', function ($join) use ($startDate, $endDate) {
                $join->on('absens.nik', '=', 'users.employee_code')
                    ->whereBetween('absens.tanggal', [$startDate, $endDate])
                    ->whereNotNull('project');
            })
            ->where('karyawan.unit_bisnis', $company->unit_bisnis);

        // Memfilter berdasarkan organisasi jika terpilih
        if ($selectedOrganization) {
            $query->where('karyawan.organisasi', $selectedOrganization);
        }

        // Mengambil data absensi dari database
        $data1 = $query->select('users.*', 'absens.*')
            ->orderBy('users.name')
            ->get();

        // Mengirim data ke tampilan
        return view('pages.absen.index', compact('data1', 'endDate', 'startDate', 'selectedOrganization'));
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
            ->leftJoin('absens', function ($join) use ($startDate, $endDate) {
                $join->on('absens.nik', '=', 'users.employee_code')
                    ->whereBetween('absens.tanggal', [$startDate, $endDate])
                    ->whereNotNull('absens.project_backup');
            })
            ->where('karyawan.unit_bisnis', $company->unit_bisnis);

        // Mengambil data absensi dari database
        $data1 = $query->select('users.*', 'absens.*')
            ->orderBy('users.name')
            ->get();

        // Mengirim data ke tampilan
        return view('pages.absen.backup.databackup', compact('data1', 'endDate', 'startDate'));
    }

    public function filterByOrganization(Request $request)
    {
        $selectedOrganization = $request->input('organization');
        return redirect()->route('absen.index', ['organization' => $selectedOrganization]);
    }


    public function exportAttendence()
    {
        // Get Bulan
        $loggedInUserNik = auth()->user()->employee_code;
        $company = Employee::where('nik', $loggedInUserNik)->first();

        // Dapatkan nilai unit bisnis dari request
        $unitBisnis = $company->unit_bisnis;

        // Dapatkan tanggal awal dan akhir periode
        $today = now();
        $startDate = $today->day >= 21 ? $today->copy()->day(21) : $today->copy()->subMonth()->day(21);
        $endDate = $today->day >= 21 ? $today->copy()->addMonth()->day(20) : $today->copy()->day(20);

        // Buat instance dari kelas AttendenceExport dengan rentang waktu
        $export = new AttendenceExport($unitBisnis, $loggedInUserNik, $startDate, $endDate);

        // Ekspor data ke Excel
        return Excel::download($export, 'attendence_export_' . strtolower($startDate->format('F')) . '.xlsx');
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
            // Fet Data From Device User
            $lat = $request->input('latitude');
            $long = $request->input('longitude');
            $status = $request->input('status');
            
            // Hitung Radius
            $distance = $this->calculateDistance($kantorLatitude, $kantorLongtitude, $lat, $long);

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

            $absensi = Absen::where('nik', $nik)
                ->whereBetween('tanggal', [$startDate, $endDate])
                ->whereNotNull('project_backup')
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
}
