<?php

namespace App\Http\Controllers\Absen;

use App\Http\Controllers\Controller;
use App\Employee;
use App\User;
use App\Absen;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
class LogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (Auth::check()) {
            $user = Auth::user();
                if($user->id){
                    $karyawan = Employee::all();
                    $lastAbsensi = $user->absen()->latest()->first();
                    // Get Data Karyawan
                    $userId = Auth::id();
                    $hariini = now()->format('Y-m-d');
                    $datakaryawan = Employee::join('users', 'karyawan.nik', '=', 'users.employee_code')
                        ->where('users.employee_code', $userId)
                        ->select('karyawan.*')
                        ->get();

                    $employeCode = User::where('id',$userId)->first();

                    // Get Log Absensi
                    $logs = Absen::where('user_id', $employeCode->employee_code)
                        ->whereDate('tanggal', $hariini)
                        ->get();
                    $startOfMonth = Carbon::now()->startOfMonth();
                    $endOfMonth = Carbon::now()->endOfMonth();

                    // Get logs for the month
                    $logsmonths = Absen::where('user_id', $employeCode->employee_code)
                    ->whereBetween('tanggal', [$startOfMonth, $endOfMonth])
                    ->orderByDesc('tanggal')
                    ->get();
                    
                    $bulan = $request->input('bulan');

                    if ($bulan) {
                        $logsfilter = DB::table('absens')
                            ->whereMonth('tanggal', '=', date('m', strtotime($bulan)))
                            ->whereYear('tanggal', '=', date('Y', strtotime($bulan)))
                            ->where('user_id',$employeCode->employee_code)
                            ->get();
                    } else {
                        $logsfilter = DB::table('absens')
                        ->where('user_id',$employeCode->employee_code)
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

                    // Greating
                    date_default_timezone_set('Asia/Jakarta'); // Set timezone sesuai dengan lokasi Anda
                    $hour = date('H'); // Ambil jam saat ini

                    if ($hour >= 5 && $hour < 12) {
                        $greeting = 'Selamat Pagi';
                    } else if ($hour >= 12 && $hour < 18) {
                        $greeting = 'Selamat Siang';
                    } else {
                        $greeting = 'Selamat Malam';
                    }

                    $totalDaysInMonth = Carbon::now()->daysInMonth;

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

                    return view('pages.absen.log',compact('karyawan','alreadyClockIn','alreadyClockOut','isSameDay','datakaryawan','logs','greeting','logsmonths','logsfilter','daysWithNoLogs'));
                }else{
                    return redirect('pages.auth.login')->intended('login');
                }
        }else{
            return redirect()->route('login');
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
        //
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
