<?php

namespace App\Http\Controllers\Koperasi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Koperasi\Koperasi;
use App\Koperasi\Anggota;
use App\Koperasi\SettingLoan;
use App\Koperasi\LoanPayment;
use App\Koperasi\Saving;
use App\Employee;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Koperasi\Loan;

class KoperasiController extends Controller
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

        // Loan Settings
        $loansettings = SettingLoan::where('company',$company->unit_bisnis)->get();

        // Redirect View 
        $koperasi = Koperasi::where('company', $company->unit_bisnis)->get();
        $anggotaPending = Anggota::where('company', $company->unit_bisnis)
                        ->where('member_status', 'review')
                        ->get();

        $anggota = Anggota::where('company', $company->unit_bisnis)
                        ->where('member_status', 'active')
                        ->count();
        
        $totalSimpanan = Saving::sum('totalsimpanan');

        //Daftar Pengajuan
        $pinjamanData = Loan::where('company', $company->unit_bisnis)
                        ->where('status','waiting')        
                        ->get(); 

        // Count Data
        $anggotapending = $anggotaPending->count();
        $pinjaman = $pinjamanData->count();

        return view('pages.app-setting.koperasi.index', compact('koperasi','anggotaPending','anggota','loansettings','pinjamanData','anggotapending','pinjaman','totalSimpanan'));
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

    public function anggotapage()
    {
        // Cek User Login 
        $code = Auth::user()->employee_code;
        $company = Employee::where('nik', $code)->first();

        $anggota = Anggota::where('company', $company->unit_bisnis)
                        ->where('member_status', 'active')
                        ->get();
        return view('pages.app-setting.koperasi.anggota',compact('anggota'));
    }

    public function pendinganggota()
    {
        $code = Auth::user()->employee_code;
        $company = Employee::where('nik', $code)->first();

        $anggotaPending = Anggota::where('company', $company->unit_bisnis)
                        ->where('member_status', 'review')
                        ->get();
        
        return view('pages.app-setting.koperasi.pengajuananggota',compact('anggotaPending'));
    }

    public function pinjamananggota()
    {
        $code = Auth::user()->employee_code;
        $company = Employee::where('nik', $code)->first();

        //Daftar Pengajuan
        $pinjamanData = Loan::where('company', $company->unit_bisnis)
                        ->where('status','waiting')        
                        ->get(); 
        
        return view('pages.app-setting.koperasi.pinjaman',compact('pinjamanData'));
    }

    // Currency Format
    private function formatCurrency($number)
    {
        if ($number >= 1000000000) {
            return 'Rp ' . number_format($number / 1000000000, 2) . ' Miliar';
        } elseif ($number >= 1000000) {
            return 'Rp ' . number_format($number / 1000000, 2) . ' Juta';
        } elseif ($number >= 1000) {
            return 'Rp ' . number_format($number / 1000, 2) . ' Ribu';
        }

        return 'Rp ' . number_format($number, 2);
    }

    // Dashboard Koperasi
    public function dashboard()
    {
        // Cek User Login 
        $code = Auth::user()->employee_code;
        $company = Employee::where('nik', $code)->first();

        $totalSimpanan = Saving::sum('totalsimpanan');
        $totalPiutang = LoanPayment::sum('sisahutang');

        // count anggota
        $anggota = Anggota::where('company', $company->unit_bisnis)
        ->where('member_status', 'active')
        ->count();

        $formattedTotalPiutang = $this->formatCurrency($totalPiutang);
        $formattedTotalSimpanan = $this->formatCurrency($totalSimpanan);

        // chart data
        $chartData = [
            'series' => [$totalSimpanan, $totalPiutang],
            'labels' => ['Total Simpanan', 'Total Piutang']
        ];
        
        $lineLabels = $this->generateMonthYearLabels();
        $lineData1 = $this->getMonthlySavingData();
        $lineData2 = $this->getMonthlyLoanData();

        return view('pages.app-setting.koperasi.dashboard', compact(
            'formattedTotalPiutang',
            'formattedTotalSimpanan',
            'anggota',
            'chartData',
            'lineLabels', 'lineData1', 'lineData2'
        ));
    }

    private function generateMonthYearLabels()
    {
        $labels = [];
        for ($month = 1; $month <= 12; $month++) {
            $labels[] = date("F Y", mktime(0, 0, 0, $month, 1));
        }
        return $labels;
    }

    // Fungsi untuk mendapatkan data simpanan per bulan
    private function getMonthlySavingData()
    {
        $data = [];
        for ($month = 1; $month <= 12; $month++) {
            // Query untuk mendapatkan total simpanan per bulan
            $totalSimpanan = Saving::whereMonth('created_at', $month)
                                    ->whereYear('created_at', date('Y'))
                                    ->sum('totalsimpanan');
            $data[] = $totalSimpanan;
        }
        return $data;
    }

    // Fungsi untuk mendapatkan data piutang per bulan
    private function getMonthlyLoanData()
    {
        $data = [];
        for ($month = 1; $month <= 12; $month++) {
            // Query untuk mendapatkan total piutang per bulan
            $totalPiutang = LoanPayment::whereMonth('created_at', $month)
                                        ->whereYear('created_at', date('Y'))
                                        ->sum('sisahutang');
            $data[] = $totalPiutang;
        }
        return $data;
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
            // Validasi input
            $request->validate([
                'potongan' => 'required|numeric',
                'iuran' => 'required|numeric',
                'persayaratan' => 'required|string',
            ]);

            // Cek User Login 
            $code = Auth::user()->employee_code;
            $company = Employee::where('nik', $code)->first();
            // Simpan data ke database
            Koperasi::create([
                'company' => $company->unit_bisnis,
                'potongan' => $request->potongan,
                'iuran' => $request->iuran,
                'membership' => $request->membership,
                'merchendise' => $request->merchendise,
                'tenor' => $request->tenor,
                'persayaratan' => $request->persayaratan,
            ]);

            // Redirect dengan pesan sukses
            return redirect()->route('koperasi.index')->with('success', 'Settings added successfully.');
        } catch (\Exception $e) {
            // Tangani kesalahan
            return redirect()->back()->with('error', 'Failed to add settings. ' . $e->getMessage());
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

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            // Validasi input jika diperlukan
            $request->validate([
                'potongan' => 'required|numeric',
                'iuran' => 'required|numeric',
                'persayaratan' => 'required|string',
            ]);

            // Cari data Koperasi berdasarkan ID
            $koperasi = Koperasi::findOrFail($id);

            // Update data Koperasi
            $koperasi->potongan = $request->potongan;
            $koperasi->iuran = $request->iuran;
            $koperasi->persayaratan = $request->persayaratan;
            $koperasi->merchendise = $request->merchendise;
            $koperasi->membership = $request->membership;
            $koperasi->tenor = $request->tenor;
            
            // Simpan perubahan
            $koperasi->save();

            // Redirect atau return response yang sesuai
            return redirect()->route('koperasi.index')->with('success', 'Settings Koperasi berhasil diperbarui.');
        } catch (\Exception $e) {
            // Tangani error
            return back()->withInput()->withErrors(['error' => 'Gagal memperbarui pengaturan Koperasi. Silakan coba lagi.']);
        }
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

    public function historypayment()
    {
        // Cek User Login 
        $code = Auth::user()->employee_code;
        $employee = Employee::where('nik', $code)->first();

        $pinjamansaya = Loan::where('status','approve')->first();
        
        $datasaya = LoanPayment::where('loan_id',$pinjamansaya->id)->get();
        $saldosaya = LoanPayment::where('loan_id',$pinjamansaya->id)->select('sisahutang')->first();

        return view ('pages.koperasi.payment.index', compact('datasaya','employee','saldosaya'));
    }
}
