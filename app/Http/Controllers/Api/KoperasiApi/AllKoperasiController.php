<?php

namespace App\Http\Controllers\Api\KoperasiApi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Koperasi\Koperasi;
use App\Koperasi\Anggota;
use App\Koperasi\Loan;
use App\Koperasi\Saving;
use App\Koperasi\LoanPayment;
use App\Koperasi\SettingLoan;
use App\Employee;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Slack;
use App\ModelCG\Schedule;
use App\Absen;
use Illuminate\Support\Str;
use App\Mail\Koperasi\PengajuanAnggota;
use App\Mail\Koperasi\PengajuanAnggotaReject;
use Illuminate\Support\Facades\Mail;

class AllKoperasiController extends Controller
{

    public function index(Request $request)
    {
        try {
            $token = $request->bearerToken();
            // Authenticate the user based on the token
            $user = Auth::guard('api')->user();
            $employeeCode = $user->name;

            // Fetch employee's business unit
            $unitBisnis = Employee::where('nik', $employeeCode)->value('unit_bisnis');

            // Check if the user is a cooperative member
            $anggota = Anggota::where('employee_code', $employeeCode)->where('member_status','active')->first();
            $anggotaStatus = Anggota::where('employee_code', $employeeCode)->first();

            // If not a member, return available cooperative data for the business unit
            if (!$anggota) {
                $koperasi = Koperasi::where('company', $unitBisnis)->get();
                return response()->json([
                    'success' => false,
                    'message' => 'You are not a cooperative member.',
                    'status_anggota' => 'Not Member',
                    'data' => $koperasi
                ], 200);
            }

            // If user is a cooperative member, fetch their savings data
            $datasaya = Saving::where('employee_id', $employeeCode)->get();
            $saldosaya = Saving::where('employee_id', $employeeCode)
                                ->select('totalsimpanan')
                                ->orderBy('created_at', 'desc')
                                ->first();

            // Check if the member has a loan
            $loan = Loan::where('employee_code', $employeeCode)
                        ->orderBy('created_at', 'desc')
                        ->first();

            // If no loan, check if the member is eligible for applying a loan
            if (!$loan) {
                $today = now();
                $start_date = $today->day >= 21 ? $today->copy()->subMonth()->day(21) : $today->copy()->subMonths(2)->day(21);
                $end_date = $today->day >= 21 ? $today->copy()->day(20) : $today->copy()->subMonth()->day(20);

                // Check eligibility: member for 3 months, no outstanding loan, and full attendance
                $isMemberForThreeMonths = Anggota::where('employee_code', $employeeCode)
                                                ->where('member_status', 'active')
                                                ->where('join_date', '<=', now()->subMonths(3))
                                                ->exists();
                
                $hasNoOutstandingLoan = Anggota::where('employee_code', $employeeCode)
                                                ->where('loan_status', 'noloan')
                                                ->exists();

                $scheduleDays = Schedule::where('employee', $employeeCode)
                                        ->whereBetween('tanggal', [$start_date, $end_date])
                                        ->where('shift', '<>', 'OFF')
                                        ->count();

                $attendanceDays = Absen::where('nik', $employeeCode)
                                    ->whereBetween('tanggal', [$start_date, $end_date])
                                    ->count();

                $hadFullAttendance = $attendanceDays === $scheduleDays - 1;

                $canApplyForLoan = $isMemberForThreeMonths && $hasNoOutstandingLoan && $hadFullAttendance;

                // If eligible for a loan, return loan application requirements
                return response()->json([
                    'success' => true,
                    'syarat1' => $isMemberForThreeMonths,
                    'syarat2' => $hasNoOutstandingLoan,
                    'syarat3' => $hadFullAttendance,
                    'status_anggota' => $anggotaStatus->member_status,
                    'onloan_status' => $loan,
                    'eligibility' => $canApplyForLoan,
                    'savings' => $datasaya,
                    'saldo_simpanan' => $saldosaya,
                ], 200);
            }

            // If the member has a loan, return the remaining loan details
            $pinjaman = LoanPayment::where('loan_id', $loan->id)
                                ->orderBy('created_at', 'desc')
                                ->first();

            return response()->json([
                'success' => true,
                'message' => 'Loan data retrieved.',
                'status_anggota' => $anggotaStatus->member_status,
                'onloan_status' => $loan,
                'remaining_loan' => $pinjaman,
                'savings' => $datasaya,
                'saldo_simpanan' => $saldosaya,
            ], 200);

        } catch (\Exception $e) {
            // Handle exception and return JSON error response
            return response()->json([
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage()
            ], 500);
        }
    }

    public function cekAnggota(Request $request)
    {
        try {
            $token = $request->bearerToken();
            // Authenticate the user based on the token
            $user = Auth::guard('api')->user();
            $employeeCode = $user->name;
            $unitBisnis = Employee::where('nik', $employeeCode)->value('unit_bisnis');
            $anggotaStatus = Anggota::where('employee_code', $employeeCode)->first();

            if (!$anggotaStatus) {
                $koperasi = Koperasi::where('company', $unitBisnis)->get();
                return response()->json([
                    'message' => 'You are not a cooperative member.',
                    'status_anggota' => 'Not Member',
                    'data' => $koperasi
                ], 200);
            }


            return response()->json(['status_anggota' => $anggotaStatus->member_status], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }



    public function terms(Request $request)
    {
        try {
            $token = $request->bearerToken();
            // Authenticate the user based on the token
            $user = Auth::guard('api')->user();
            $employeeCode = $user->name;
            $unitBisnis = Employee::where('nik', $employeeCode)->value('unit_bisnis');
            $koperasi = Koperasi::where('company', $unitBisnis)->get();
            return response()->json(['data' => $koperasi], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
        
    }

    public function keanggotaan(Request $request)
    {

        try {
            $token = $request->bearerToken();
            // Authenticate the user based on the token
            $user = Auth::guard('api')->user();
            $employeeCode = $user->name;
            $employeeData = Employee::where('nik', $employeeCode)->first();

            // Buat instance model Anggota
            $anggotaDaftar = new Anggota();
            
            // Isi model dengan data dari formulir
            $anggotaDaftar->nama = $employeeData->nama;
            $anggotaDaftar->employee_code = $employeeData->nik;
            $anggotaDaftar->company = $employeeData->unit_bisnis;
    
            $anggotaDaftar->join_date = '-';
            $anggotaDaftar->member_status = 'review';
            $anggotaDaftar->loan_limit = 0;
            $anggotaDaftar->loan_status = 'noloan';
            $anggotaDaftar->saldosimpanan = 0;
            $anggotaDaftar->save();
    
            // Kembalikan response JSON sukses
            return response()->json([
                'success' => true,
                'message' => 'Thank you for registering, we are reviewing your data first.',
                'data' => $anggotaDaftar
            ], 201);
    
        } catch (\Exception $e) {
            // Tangani pengecualian di sini dan kembalikan response JSON error
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan data: ' . $e->getMessage()
            ], 500);
        }
    }

    public function pengajuanPinjaman(Request $request)
    {
        try 
        {
            $token = $request->bearerToken();
            // Authenticate the user based on the token
            $user = Auth::guard('api')->user();
            $employeeCode = $user->name;
            $employeeData = Employee::where('nik', $employeeCode)->first();

            $koperasi = Koperasi::where('company', $employeeData->unit_bisnis)->first();
            $pinjamanAwal = $request->amount;
            $tenor = $koperasi->tenor;
            $membership = $koperasi->membership / 100;
            $merchendise = $koperasi->merchendise / 100;
            $persentase = $membership + $merchendise;
            $kalkulasi = $pinjamanAwal * $persentase;
            $totalPinjaman = $pinjamanAwal + $kalkulasi;

            $instalment = round($totalPinjaman / $tenor);
            $loanUuid = Str::uuid();
            // Simpan data ke database
            Loan::create([
                'id' => $loanUuid,
                'company' => $employeeData->unit_bisnis,
                'nama' => $employeeData->nama,
                'employee_code' => $employeeCode,
                'amount' => $totalPinjaman,
                'tenor' => $tenor,
                'instalment' => $instalment,
                'status' => 'waiting',
                'approve_by' => '-',
            ]);

            Anggota::where('employee_code', $employeeCode)->update(['loan_status' => 'onloan']);
            $today = Carbon::now()->format('Y-m-d');
            LoanPayment::create([
                'loan_id' => $loanUuid,
                'tanggal_pembayaran' => $today,
                'jumlah_pembayaran' => 0,
                'sisahutang' => $totalPinjaman,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Thank you for registering, we are reviewing your data first.'
            ], 201);


        } catch (\Exception $e) {
            // Tangani pengecualian di sini dan kembalikan response JSON error
            return response()->json([
                'success' => false,
                'message' => 'error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function cekLimit(Request $request)
    {
        try {
            $token = $request->bearerToken();
            // Authenticate the user based on the token
            $user = Auth::guard('api')->user();
            $employeeCode = $user->name;
            $employeeData = Employee::where('nik', $employeeCode)->first();
            $unitBisnis = Employee::where('nik', $employeeCode)->value('unit_bisnis');
            $koperasi = Koperasi::where('company', $unitBisnis)->select('membership','merchendise','tenor')->get();
            // Buat instance model Anggota
            $datasaya = Saving::where('employee_id',$employeeCode)->get();
            $dataSaya = Anggota::where('employee_code',$employeeCode)
                            ->select('saldosimpanan')->first();

            if (!$dataSaya) {
                abort(404, 'Data anggota tidak ditemukan.');
            }
            
            $saldosimpanan = $dataSaya->saldosimpanan;
            $loansettings = SettingLoan::where('company',$employeeData->unit_bisnis)->get();

            foreach ($loansettings as $datapinjaman) {
                if ($saldosimpanan >= $datapinjaman->min_saving && $saldosimpanan <= $datapinjaman->max_saving) {
                    $limitpinjaman = $datapinjaman->max_limit;
                    break;
                }
            }
    
            // Kembalikan response JSON sukses
            return response()->json([
                'success' => true,
                'limit_saya' => $limitpinjaman,
                'koperasi' => $koperasi,
            ], 201);
    
        } catch (\Exception $e) {
            // Tangani pengecualian di sini dan kembalikan response JSON error
            return response()->json([
                'success' => false,
                'message' => 'error: ' . $e->getMessage()
            ], 500);
        }
    }
}
