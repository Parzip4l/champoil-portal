<?php

namespace App\Http\Controllers\Koperasi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Employee;
use App\Koperasi\Loan;
use App\Koperasi\Saving;
use App\Koperasi\SettingLoan;
use App\Koperasi\Anggota;
use App\Koperasi\Koperasi;
use App\Koperasi\LoanPayment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class PengajuanPinjamanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $code = Auth::user()->employee_code;
        $employee = Employee::where('nik', $code)->first();
        if (!$employee) {
            abort(404, 'Data perusahaan tidak ditemukan.');
        }

        $dataSaya = Anggota::where('employee_code',$code)
                            ->select('saldosimpanan')->first();

        // Pastikan data anggota ditemukan
        if (!$dataSaya) {
            abort(404, 'Data anggota tidak ditemukan.');
        }

        $saldosimpanan = $dataSaya->saldosimpanan;

        // Data Koperasi
        $koperasi = Koperasi::where('company', $employee->unit_bisnis)->first();

        // Loan Settings
        $loansettings = SettingLoan::where('company',$employee->unit_bisnis)->get();

        $minsaving = 0;
        $maxsaving = 0;
        $maxlimit = 0;   
        $limitpinjaman = 0;     

        foreach ($loansettings as $datapinjaman) {
            if ($saldosimpanan >= $datapinjaman->min_saving && $saldosimpanan <= $datapinjaman->max_saving) {
                $limitpinjaman = $datapinjaman->max_limit;
                break;
            }
        }

        return view('pages.koperasi.pengajuan.index', compact('limitpinjaman','saldosimpanan','employee','koperasi'));

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
        try {
            DB::beginTransaction();
            // Validasi input
            $request->validate([
                'amount' => 'required',
                'tenor' => 'required',
            ]);

            // Cek User Login 
            $code = Auth::user()->employee_code;
            $company = Employee::where('nik', $code)->first();

            $loanUuid = Str::uuid();
            // Simpan data ke database
            Loan::create([
                'id' => $loanUuid,
                'company' => $company->unit_bisnis,
                'nama' => $company->nama,
                'employee_code' => $code,
                'amount' => $request->amount,
                'tenor' => $request->tenor,
                'instalment' => $request->instalment,
                'status' => 'waiting',
                'approve_by' => '-',
            ]);

            Anggota::where('employee_code', $code)->update(['loan_status' => 'onloan']);
            $today = Carbon::now()->format('Y-m-d');
            LoanPayment::create([
                'loan_id' => $loanUuid,
                'tanggal_pembayaran' => $today,
                'jumlah_pembayaran' => 0,
                'sisahutang' => $request->amount,
            ]);

            DB::commit();

            // Redirect dengan pesan sukses
            return redirect()->route('koperasi-page.index')->with('success', 'Pengajuan Pinjaman Berhasil Di Ajukan.');
        } catch (\Exception $e) {
            DB::rollback();
            // Tangani kesalahan
            return redirect()->back()->with('error', 'Gagal Mengajukan Pinjaman. ' . $e->getMessage());
        }
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

    public function ApprovePinjaman($employee_code)
    {
        $code = Auth::user()->employee_code;
        $employee = Employee::where('nik', $code)->first();
        $companyData = $employee->unit_bisnis;
        $today = Carbon::now()->format('Y-m-d');

        Loan::where('employee_code', $employee_code)
                    ->where('company',$companyData)
                    ->update(['status' => 'approve', 'approve_by' => $code]);

        $records = Employee::where('nik', $employee_code)->get();
        $html = '```Halo ```' .strtoupper($employee->nama). '``` yang terhormat,

Kami dengan senang hati menginformasikan bahwa pengajuan pinjaman Anda di TRUEST telah disetujui. Selamat! Anda sekarang dapat menggunakan dana pinjaman sesuai dengan ketentuan yang telah disepakati.

Berikut adalah beberapa langkah yang perlu Anda ikuti untuk mengakses dana pinjaman Anda:

1. Login ke akun TRUEST Anda.
2. Periksa saldo pinjaman Anda yang telah ditambahkan.
3. Ikuti instruksi lebih lanjut yang tersedia di aplikasi untuk penggunaan dana.

Jika Anda memiliki pertanyaan atau membutuhkan bantuan lebih lanjut, jangan ragu untuk menghubungi tim dukungan kami.

Terima kasih atas kepercayaan Anda kepada TRUEST.

Salam hormat,

TRUEST Team```';

        foreach($records as $row){
            push_notif_wa($html,'','',$row->telepon,'');
        }

        return redirect()->back()->with('success', 'Data has been update');
    }

    public function RejectPinjaman($employee_code)
    {
        $code = Auth::user()->employee_code;
        $employee = Employee::where('nik', $code)->first();
        $companyData = $employee->unit_bisnis;
        $now = Carbon::now();

        Loan::where('employee_code', $employee_code)
                    ->where('company',$companyData)
                    ->update(['status' => 'rejected', 'approve_by' => $code]);
        
        $records = Employee::where('nik', $employee_code)->get();
        $html = '```Halo ```' .strtoupper($employee->nama). '``` yang terhormat,

Kami ingin menginformasikan bahwa pengajuan pinjaman Anda di TRUEST tidak dapat disetujui pada saat ini. Kami mohon maaf atas ketidaknyamanannya.

Jika Anda memiliki pertanyaan lebih lanjut atau ingin mengetahui alasan penolakan, silakan menghubungi tim dukungan kami. Kami akan dengan senang hati membantu Anda.

Terima kasih atas perhatian dan pengertian Anda.

Salam hormat,

TRUEST Team```';


        foreach($records as $row){
            push_notif_wa($html,'','',$row->telepon,'');
        }

        return redirect()->back()->with('success', 'Data has been update');
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