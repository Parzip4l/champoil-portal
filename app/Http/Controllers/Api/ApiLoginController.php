<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Cache;
use App\Http\Controllers\Controller;
use App\Models\Schedule;
use App\Models\Project;
use App\Models\absen;
use App\Models\Payrol;
use App\Models\Payrollns;
use App\Models\Employee;

class ApiLoginController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            $user = $request->user();
            $token = $request->user()->createToken('mobile')->plainTextToken;

            $userData = [
                'name' => $user->name,
                'email' => $user->email,
                'permission' => $user->permission,
                'employee_code' => $user->employee_code,
            ];

            Cache::put('nik' . $request->user()->name, $token, 250000);
            return response()->json(['token' => $token, 'user' => $userData], 200);
        } else {
            return response()->json(['error' => 'Email atau password salah.'], 401);
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json(['message' => 'Logout berhasil.'], 200);
    }

    public function clockin(Request $request)
    {   
        $user = Auth::user();
        $nik = Auth::user()->employee_code;

        $today = Carbon::now()->format('Y-m-d');

        $schedulebackup = Schedule::where('employee', $nik)
            ->whereDate('tanggal', $today)
            ->get();

        $project_id = null;
        foreach ($schedulebackup as $databackup) {
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
        } else {
            $latitudeProject = -6.1366045;
            $longtitudeProject = 106.7601449;
            $kantorLatitude = -6.1366045; 
            $kantorLongtitude = 106.7601449; 
        }

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
            $absensi->latitude = $latitudeProject;
            $absensi->longtitude = $longtitudeProject;
            $absensi->status = $status;
            $absensi->save();

            return response()->json(['message' => 'Clockin success, Happy Working Day!']);
        } else {
            return response()->json(['message' => 'Clockin Rejected, Outside Radius!']);
        }
    }

    public function payslipuser()
    {
        $employeeCode = auth()->user()->employee_code;

        // Ambil semua payslip berdasarkan employee_code
        $dataKaryawan = Employee::where('nik', $employeeCode)->first();
        $karyawan = json_decode($dataKaryawan, true);

        if ($karyawan['organisasi'] === 'Management Leaders') {
            $payslips = Payrol::where('employee_code', $employeeCode)->get();
        } else {
            $payslips = Payrollns::where('employee_code', $employeeCode)->get();
        }

        // Modify the response to return JSON data
        $payslipsData = $payslips->toArray();
        
        return response()->json(['payslips' => $payslipsData]);
    }
}
