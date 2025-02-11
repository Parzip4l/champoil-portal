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
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\Exports\AnggotaKoperasoExport;

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
        
        $anggotaOnLoan = Anggota::where('company', $company->unit_bisnis)
                        ->where('member_status', 'active')
                        ->where('loan_status', 'onloan')
                        ->get();
        
        $latestSavings = Saving::select('employee_id', 'totalsimpanan')
                        ->orderBy('created_at', 'desc')
                        ->distinct('employee_id')
                        ->get()
                        ->groupBy('employee_id')
                        ->map(function ($group) {
                            // Get the most recent record for each employee_id
                            return $group->first();
                        });
        $totalSimpanan = $latestSavings->sum('totalsimpanan') - 100000;

        
        //Daftar Pengajuan
        $pinjamanData = Loan::where('company', $company->unit_bisnis)
                        ->where('status','waiting')        
                        ->get(); 

        // Count Data
        $anggotapending = $anggotaPending->count();
        $pinjaman = $pinjamanData->count();

        return view('pages.app-setting.koperasi.index', compact('koperasi','anggotaPending','anggota','loansettings','pinjamanData','anggotapending','pinjaman','totalSimpanan','anggotaOnLoan'));
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

    public function anggotapage(Request $request)
    {
        // Cek User Login 
        $code = Auth::user()->employee_code;
        $company = Employee::where('nik', $code)->first();

        // Ambil filter dari request, default ke 'all'
        $filterStatus = $request->input('member_status', 'all');
        $loanStatus = $request->input('loan_status', 'all');

        $query = Anggota::where('company', $company->unit_bisnis)
                        ->whereNot('member_status', 'review')
                        ->whereNot('member_status', 'reject');
        
        // Terapkan filter jika bukan 'all'
        if ($filterStatus !== 'all') {
            $query->where('member_status', $filterStatus);
        }

        if ($loanStatus !== 'all') {
            $query->where('loan_status', $loanStatus);
        }

        $anggota = $query->get();

        // Tambahkan saldo simpanan untuk setiap anggota
        foreach ($anggota as $dataAnggota) {
            $lastSaving = Saving::where('employee_id', $dataAnggota->employee_code)
                ->where('jumlah_simpanan', '!=', 0)
                ->where('totalsimpanan', '!=', 0)
                ->latest('created_at')
                ->first();
    
            $dataAnggota->saldo_simpanan = $lastSaving ? $lastSaving->totalsimpanan : 0;
        }

        return view('pages.app-setting.koperasi.anggota', compact('anggota', 'filterStatus','loanStatus'));
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

        

        $latestSavings = Saving::select('employee_id', 'totalsimpanan')
                            ->orderBy('created_at', 'desc')
                            ->distinct('employee_id')
                            ->get()
                            ->groupBy('employee_id')
                            ->map(function ($group) {
                                // Get the most recent record for each employee_id
                                return $group->first();
                            });

        $latestPiutang = LoanPayment::select('loan_id', 'sisahutang')
                            ->orderBy('created_at', 'desc')
                            ->distinct('loan_id')
                            ->get()
                            ->groupBy('loan_id')
                            ->map(function ($group) {
                                // Get the most recent record for each employee_id
                                return $group->first();
                            });

        $simpananData = Saving::where('jumlah_simpanan', '!=', 0)
                            ->where('totalsimpanan', '!=', 0)
                            ->orderBy('created_at', 'desc')
                            ->get();

        $totalPiutang = $latestPiutang->sum('sisahutang');
        // Calculate the total of `totalsimpanan` from the most recent records
        $totalSimpanan = $latestSavings->sum('totalsimpanan') - 100000;
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
            'lineLabels', 'lineData1', 'lineData2','simpananData'
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

        $pinjamansaya = Loan::where('status','approve')->where('employee_code',$code)->first();
        $datasaya = LoanPayment::where('loan_id',$pinjamansaya->id)->get();
        $saldosaya = LoanPayment::where('loan_id',$pinjamansaya->id)->select('sisahutang')->orderBy('created_at', 'desc')
        ->first();
        return view ('pages.koperasi.payment.index', compact('datasaya','employee','saldosaya'));
    }

    public function downloadExcel()
    {
        // Membuat Spreadsheet baru
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set judul kolom di Excel (sesuai format gambar)
        $sheet->setCellValue('A1', 'No')
            ->setCellValue('B1', 'Nama')
            ->setCellValue('C1', 'Tenor')
            ->setCellValue('D1', 'Total Pinjaman')
            ->setCellValue('E1', 'Angsuran')
            ->setCellValue('F1', 'Tanggal Approval')
            ->setCellValue('G1', 'Tanggal Angsuran')
            ->setCellValue('H1', 'Status Angsuran');

        // Mengambil data dari database tabel anggota dan pengajuan pinjaman
        // Mengambil hanya anggota yang statusnya 'onloan'
        $anggotaData = Anggota::where('loan_status', 'onloan')->get();
        $row = 2; // Mulai dari baris kedua untuk data

        foreach ($anggotaData as $index => $anggota) {
            // Cek apakah anggota memiliki pengajuan pinjaman dengan status 'approve'
            $pengajuan = Loan::where('employee_code', $anggota->employee_code)
                            ->where('status', 'approve')
                            ->first();

            // Jika ada pengajuan pinjaman dengan status 'approve'
            if ($pengajuan) {
                $tenor = $pengajuan->tenor;
                $totalPinjaman = $pengajuan->amount;
                $angsuran = $pengajuan->instalment;
                $approvalDate = $pengajuan->updated_at;

                // Set tanggal angsuran
                for ($i = 0; $i < $tenor; $i++) {
                    $dueDate = \Carbon\Carbon::parse($approvalDate)->addMonths($i + 1)->format('Y-m-d');

                    

                    // Cek status pembayaran dari tabel loan_payments berdasarkan loan_id
                    $loanPayment = LoanPayment::where('loan_id', $pengajuan->id)
                                                ->where('tanggal_pembayaran', $dueDate)
                                                ->first();

                    $statusAngsuran = $loanPayment ? 'LUNAS' : 'BELUM LUNAS';

                    // Isi data ke dalam Excel
                    $sheet->setCellValue('A' . $row, $index + 1)
                        ->setCellValue('B' . $row, $anggota->nama)
                        ->setCellValue('C' . $row, $tenor)
                        ->setCellValue('D' . $row, $totalPinjaman)
                        ->setCellValue('E' . $row, $angsuran)
                        ->setCellValue('F' . $row, $approvalDate->format('d/m/Y'))
                        ->setCellValue('G' . $row, $dueDate)
                        ->setCellValue('H' . $row, $statusAngsuran);

                    $row++;
                }
            }
        }

        // Siapkan nama file
        $fileName = 'Laporan_Anggota_Pinjaman.xlsx';
        $filePath = storage_path('app/public/' . $fileName);

        // Simpan file Excel di storage
        $writer = new Xlsx($spreadsheet);
        $writer->save($filePath);

        // Mengunduh file Excel
        return response()->download($filePath)->deleteFileAfterSend(true);
    }

    public function exportAnggota(Request $request)
    {
        $filterStatus = $request->input('member_status', 'all');
        $loanStatus = $request->input('loan_status', 'all');

        return Excel::download(new AnggotaKoperasoExport($filterStatus, $loanStatus), 'anggota.xlsx');
    }


}
