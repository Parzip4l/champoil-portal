<?php

namespace App\Http\Controllers\Absen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Absen;
use App\Employee;
use App\ModelCG\Schedule;
use App\ModelCG\ScheduleBackup;
use App\ModelCG\Project;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AbsenController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $today = Carbon::today();
        $employee = Employee::all();
        $absens = DB::table('absens')
            ->whereDate('tanggal', $today)
            ->get();
        $namauser = Auth::user()->employee_code;
        foreach ($absens as $absen) {
            $karyawan = Employee::find($absen->nik);
            if ($karyawan) {
                $absen->nama_karyawan = $karyawan->nama;
            } else {
                $absen->nama_karyawan = "Karyawan tidak ditemukan";
            }
        }
        $today = Carbon::today()->toDateString();
        $startDate = Carbon::now()->startOfMonth();
        $endDate = Carbon::now()->endOfMonth();

        $data12 = Absen::leftJoin('karyawan', 'karyawan.nik', '=', 'absens.nik')
            ->whereDate('absens.tanggal', $today)
            ->orWhereNull('absens.tanggal')
            ->select('karyawan.nama', 'karyawan.nik','absens.clock_in', 'absens.clock_out','absens.tanggal','absens.nik')
            ->get();

            $tanggal = now()->format('Y-m-d');
            $data1 = DB::table('users')->leftJoin('absens', function($join) use($startDate,$endDate) {
                         $join->on('absens.nik', '=', 'users.employee_code')
                              ->whereBetween('absens.tanggal', [$startDate,$endDate]);
                     })->select('users.*', 'absens.*')
                       ->orderBy('users.name')
                       ->get();

        return view('pages.absen.index',compact('absens','employee','data1','endDate','startDate'));
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
        $user = Auth::user();
        $nik = Auth::user()->employee_code;

        $kantorLatitude = -6.1369556;
        $kantorLongtitude = 106.7601356;

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
            $absensi->clock_in = now()->format('H:i');
            $absensi->latitude = $lat;
            $absensi->longtitude = $long;
            $absensi->status = $status;
            $absensi->save();
            return redirect()->back()->with('success', 'Clockin success, Happy Working Day!');
        } else {
            return redirect()->back()->with('error', 'Anda Diluar Radius Absen!');
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
                $absensi->clock_out = Carbon::now()->toTimeString();
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
    public function show($id)
    {
        $absensi = Absen::where('id', $id)
                     ->whereDate('tanggal', now())
                     ->firstOrFail();

        return view('pages.absen.show', compact('absensi'));
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
}
