<?php

namespace App\Http\Controllers\Absen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Absen;
use App\Employee;
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
            $absensi->clock_in = now()->toTimeString();
            $absensi->latitude = $lat;
            $absensi->longtitude = $long;
            $absensi->status = $status;
            $absensi->save();
            return redirect()->back()->with('success', 'Clockin success, Happy Working Day!');
        } else {
            return redirect()->back()->with('error', 'Anda Diluar Radius Absen!');
        }
    }

    private function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371; // radius Bumi dalam kilometer

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLon / 2) * sin($dLon / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        $distance = $earthRadius * $c; // jarak dalam kilometer

        return $distance;
    }

    public function clockout(Request $request)
    {
        $nik = Auth::user()->employee_code;
        $lat2 = $request->input('latitude_out');
        $long2 = $request->input('longitude_out');
        $absensi = Absen::where('nik', $nik)
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
