<?php

namespace App\Http\Controllers\Koperasi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Koperasi\Koperasi;
use App\Koperasi\Anggota;
use App\Koperasi\Loan;
use App\Koperasi\Saving;
use App\Koperasi\LoanPayment;
use App\Employee;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Slack;
use App\ModelCG\Schedule;
use App\Absen;
use App\Mail\Koperasi\PengajuanAnggota;
use Illuminate\Support\Facades\Mail;


class AnggotaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Cek User Login 
        $code = Auth::user()->employee_code;
        $company = Employee::where('nik', $code)->first();

        $employee = Employee::where('nik', $code)->with('payrolinfo')->first();
        $koperasi = Koperasi::where('company', $company->unit_bisnis)->first();
        $today = now();
        $start_date = $today->day >= 21 ? $today->copy()->subMonth()->day(21) : $today->copy()->subMonths(2)->day(21);
        $end_date = $today->day >= 21 ? $today->copy()->day(20) : $today->copy()->subMonth()->day(20);

        // Persayaratan Cek 
        $isMemberForThreeMonths = Anggota::where('employee_code', $code)
                    ->where('company', $company->unit_bisnis)
                    ->where('member_status', 'active')
                    ->where('join_date', '<=', now()->subMonths(3))
                    ->exists();

        // Check if the user is not currently on loan
        $hasNoOutstandingLoan = Anggota::where('employee_code', $code)
                            ->where('loan_status', 'noloan')
                            ->exists();

        $scheduleDays = Schedule::where('employee', $code)
                ->whereBetween('tanggal', [$start_date, $end_date])
                ->where('shift', '<>', 'OFF')
                ->count();

        $attendanceDays = Absen::where('nik', $code)
                ->whereBetween('tanggal', [$start_date, $end_date])
                ->count();
                

        $hadFullAttendance = $attendanceDays === $scheduleDays-1;
        $canApplyForLoan = $isMemberForThreeMonths && $hasNoOutstandingLoan  && $hadFullAttendance;

        // Pinjaman Cek
        $loan = Loan::where('employee_code',$code)->orderBy('created_at', 'desc')
        ->first();
        $saving = Saving::where('employee_id',$code)->orderBy('created_at', 'desc')
        ->first();
        $pinjaman = null;
        // Data Pinjaman Sata
        if ($loan) {
            // Data Pinjaman Sata
            $pinjaman = LoanPayment::where('loan_id', $loan->id)->orderBy('created_at', 'desc')
            ->first();
        }

        // Cek Member 
        $datasaya = Anggota::where('employee_code',$company->nik)->first();
        return view ('pages.koperasi.index', compact('datasaya','employee','koperasi','loan','pinjaman','saving','canApplyForLoan', 'isMemberForThreeMonths', 'hasNoOutstandingLoan', 'hadFullAttendance'));
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
            // Validasi data input jika diperlukan
            $request->validate([
                'nama' => 'required|string',
                'employee_code' => 'required|numeric',
                'company' => 'required|string',
            ]);

            // Buat instance model KoperasiPage
            $anggotaDaftar = new Anggota();
            
            // Isi model dengan data dari formulir
            $anggotaDaftar->nama = $request->nama;
            $anggotaDaftar->employee_code = $request->employee_code;
            $anggotaDaftar->company = $request->company;

            $anggotaDaftar->join_date = '-';
            $anggotaDaftar->member_status = 'review';
            $anggotaDaftar->loan_limit = 0;
            $anggotaDaftar->loan_status = 'noloan';
            $anggotaDaftar->saldosimpanan = 0;
            // Simpan data ke database
            $slackChannel = Slack::where('channel', 'Testing Channel')->first();
            $slackWebhookUrl = $slackChannel->url;
            $today = now()->toDateString();

            $employeeData = Employee::where('nik', $request->employee_code)->first();
            $data = [
                'text' => "Pengajuan Anggota Koperasi TRUEST",
                'attachments' => [
                    [
                        'fields' => [
                            [
                                'title' => 'Nama Lengkap',
                                'value' => $employeeData->nama,
                                'short' => true,
                            ],
                            [
                                'title' => 'NIK',
                                'value' => $employeeData->ktp,
                                'short' => true,
                            ],
                            [
                                'title' => 'Untuk Approval Silahkan Buka Aplikasi',
                                'short' => true,
                            ]
                        ],
                    ],
                ],
                
            ];

            $data_string = json_encode($data);

            $ch = curl_init($slackWebhookUrl);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data_string),
            ]);

            $result = curl_exec($ch);

            if ($result === false) {
                // Penanganan kesalahan jika Curl gagal
                $error = curl_error($ch);
                // Handle the error here
                return redirect()->back()->with('error', 'Terjadi kesalahan saat mengirim data ke Slack: ' . $error);
            }

            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

            if ($httpCode !== 200) {
                // Penanganan kesalahan jika Slack merespons selain status 200 OK
                // Handle the error here
                return redirect()->back()->with('error', 'Terjadi kesalahan saat mengirim data ke Slack. Kode status: ' . $httpCode);
            }

            curl_close($ch);
            $anggotaDaftar->save();

            // Redirect ke halaman yang sesuai setelah penyimpanan berhasil
            return redirect()->route('koperasi-page.index')->with('success', 'Thank you for registering, we are reviewing your data first.');
        } catch (\Exception $e) {
            // Tangani pengecualian di sini
            return back()->withError('Gagal menyimpan data: ' . $e->getMessage())->withInput();
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

    public function ApproveAnggota($employee_code)
    {
        try {
            // Mulai transaksi
            DB::beginTransaction();
        
            $code = Auth::user()->employee_code;
            $employee = Employee::where('nik', $code)->first();
            $companyData = $employee->unit_bisnis;
            $now = Carbon::now();
        
            Anggota::where('employee_code', $employee_code)
                ->where('company', $companyData)
                ->update(['member_status' => 'active', 'join_date' => $now]);
        
            $today = Carbon::now()->toDateString(); // Tanggal hari ini
            Saving::create([
                'employee_id' => $employee_code,
                'tanggal_simpan' => $today,
                'jumlah_simpanan' => 0,
                'keterangan' => '-',
                'totalsimpanan' => 0,
            ]);

            $records = Employee::where('nik', $employee_code)->get();
            $html = '```Halo ```' .strtoupper( $employee->nama ). '``` yang terhormat,

Kami ingin menginformasikan bahwa pengajuan Anda sebagai anggota koperasi di TRUEST telah disetujui. Selamat! Anda sekarang resmi terdaftar sebagai anggota koperasi kami.

Berikut adalah langkah-langkah yang perlu Anda ikuti untuk memanfaatkan layanan dan fasilitas yang tersedia:

Unduh aplikasi TRUEST versi terbaru:

Google Play Store (Android):
https://play.google.com/store/apps/details?id=co.id.truest.truest
App Store (iOS):
https://apps.apple.com/idn/app/truest/id6476389232?platform=iphone

Login menggunakan akun Anda untuk mengakses semua fitur dan layanan koperasi.

Terima kasih atas perhatian dan kerjasama Anda. Jika Anda memiliki pertanyaan atau mengalami masalah, jangan ragu untuk menghubungi tim dukungan kami.

Salam hormat,
TRUEST Team```';

            foreach($records as $row){
                push_notif_wa($html,'','',$row->telepon,'');
            }

            Mail::to($employee->email)->send(new PengajuanAnggota($employee));
        
            // Commit transaksi jika semua operasi berhasil
            DB::commit();
        
            return redirect()->back()->with('success', 'Data has been updated');
        } catch (\Exception $e) {
            // Tangani kesalahan jika ada
            DB::rollBack();
        
            return redirect()->back()->with('error', 'Failed to update data. ' . $e->getMessage());
        }
    }

    public function RejectAnggota($employee_code)
    {
        $code = Auth::user()->employee_code;
        $employee = Employee::where('nik', $code)->first();
        $companyData = $employee->unit_bisnis;
        $now = Carbon::now();

        Anggota::where('employee_code', $employee_code)
                    ->where('company', $companyData)
                    ->update(['member_status' => 'reject', 'join_date' => '-']);
        
                    $records = Employee::where('nik', $employee_code)->get();
                    $html = '```Halo ```' .strtoupper($employee->nama). '``` yang terhormat,

Kami ingin menginformasikan bahwa pengajuan Anda sebagai anggota koperasi di TRUEST tidak dapat disetujui pada saat ini. Kami mohon maaf atas ketidaknyamanannya.

Jika Anda memiliki pertanyaan lebih lanjut atau ingin mengetahui alasan penolakan, silakan menghubungi tim dukungan kami. Kami akan dengan senang hati membantu Anda.

Terima kasih atas perhatian dan pengertian Anda.

Salam hormat,

TRUEST Team```';
        
                    foreach($records as $row){
                        push_notif_wa($html,'','',$row->telepon,'');
                    }

        return redirect()->back()->with('success', 'Data has been update');
    }

    public function ReapplyAnggota($employee_code)
    {
        $code = Auth::user()->employee_code;
        $employee = Employee::where('nik', $code)->first();
        $companyData = $employee->unit_bisnis;
        $now = Carbon::now();

        Anggota::where('employee_code', $employee_code)
                    ->where('company', $companyData)
                    ->update(['member_status' => 'review', 'join_date' => '-']);

        return redirect()->back()->with('success', 'Data has been update');
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
