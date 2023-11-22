<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Cache;
use App\Http\Controllers\Controller;
use App\Schedule;
use App\Project;
use App\absen;
use App\Payrol;
use App\Payrollns;
use App\Employee;
use App\User;
use App\Absen\RequestAbsen;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\PersonalAccessToken;
use Carbon\Carbon;

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

    // Absen Balik
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
            // Log the exception for debugging purposes
            // You may want to customize this based on your logging setup
            \Log::error($e);

            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while processing the request.',
            ], 500);
        }
    }


    public function payslipuser()
    {
        try {
            if (auth()->check()) {
                $employeeCode = auth()->user()->employee_code;

                // Pastikan employeeCode tidak null sebelum mengakses database
                if ($employeeCode) {
                    $dataKaryawan = Employee::where('nik', $employeeCode)->first();

                    if ($dataKaryawan) {
                        $karyawan = json_decode($dataKaryawan, true);

                        if ($karyawan['organisasi'] === 'Management Leaders') {
                            $payslips = Payrol::where('employee_code', $employeeCode)->get();
                        } else {
                            $payslips = Payrollns::where('employee_code', $employeeCode)->get();
                        }

                        // Modify the response to return JSON data
                        $payslipsData = $payslips->toArray();

                        return response()->json(['payslips' => $payslipsData]);
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
            // Tangani kesalahan umum
            return response()->json(['error' => 'Terjadi kesalahan.'], 500);
        }
    }

    // Details Payslip
    public function PayslipDetails($id)
    {
        try {
            $payslip = Payrol::findOrFail($id);
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
    public function HistoryDataRequest()
    {
        try {
            $user = Auth::user();

            if (!$user) {
                return response()->json(['error' => 'Pengguna tidak terotentikasi.'], 401);
            }

            $employeeCode = $user->employee_code;
            $historyData = RequestAbsen::where('employee', $employeeCode)->get();

            return response()->json(['employeeCode' => $employeeCode, 'historyData' => $historyData], 200);
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
                'clock_in' => 'required',
                'clock_out' => 'required',
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

            return response()->json(['message' => 'Pengajuan berhasil diajukan'], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Handle validation errors
            return response()->json(['error' => $e->validator->errors()], 400);
        } catch (\Exception $e) {
            // Handle other exceptions
            return response()->json(['error' => 'Terjadi kesalahan.'. $e], 500);
        }
    }
}
