<?php

namespace App\Http\Controllers\CgControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\ModelCG\Payroll;
use App\Employee;
use App\Loan\LoanModel;
Use App\GardaPratama\Gp;
use App\Absen;
use Carbon\Carbon;
use App\ModelCG\Project;
use App\ModelCG\ProjectDetails;

class PayrolNS extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $dataPayroll = Payroll::all();

        return view('pages.hc.kas.payroll.index',compact('dataPayroll'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $today = now();
        $start_date = $today->day >= 21 ? $today->copy()->day(21) : $today->copy()->subMonth()->day(21);
        $end_date = $today->day >= 21 ? $today->copy()->addMonth()->day(20) : $today->copy()->day(20);

        $start_date2 = Carbon::parse($start_date)->format('d-m-Y');
        $end_date2 = Carbon::parse($end_date)->format('d-m-Y');

        $date_range = [];
        $current_date = $start_date->copy();

        while ($current_date->lte($end_date)) {
            $date_range[] = $current_date->copy();
            $current_date->addDay();
        }

        $dates_for_form = [];
        foreach ($date_range as $date) {
            $dates_for_form[$date->toDateString()] = $date->format('d F Y');
        }

        $employee = Employee::all();
        return view('pages.hc.kas.payroll.create', compact('employee','start_date2','end_date2','start_date','end_date'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validasi request
        $request->validate([
            'month' => 'required',
            'year' => 'required',
            'periode' => 'required',
            'unit_bisnis' => 'required',
        ]);

        $periodeDates = explode(' - ', $request->periode);
        $startDate = \Carbon\Carbon::createFromFormat('d-m-Y', $periodeDates[0])->format('Y-m-d');
        $endDate = \Carbon\Carbon::createFromFormat('d-m-Y', $periodeDates[1])->format('Y-m-d');

        foreach ($request->employee_code as $nik) {
            // Dapatkan data karyawan
            $employee = Employee::where('nik', $nik)->first();
            $jabatan = $employee->jabatan;

            // Dapatkan data absen berdasarkan range periode dan nik karyawan
            $absen = Absen::where('nik', $nik)
                ->whereBetween('tanggal', [$startDate, $endDate])
                ->get();

            $totalHari = 0;
            $totalGaji = 0;
            $totalHariBackup = 0;
            $totalGajiBackup = 0;
            $allowenceData= [];
            $deductiondata = [];
            $totalPotonganHutang = 0;
            $TotalGP = 0;

            //Potongan Hutang
            $potonganHutang = LoanModel::where('employee_id', $nik)
            ->where('is_paid', 0)
            ->pluck('installment_amount');

            $SisaHutangData = LoanModel::where('employee_id', $nik)
                ->where('is_paid', 0)
                ->pluck('remaining_amount');
            
            $totalPotonganHutang = $potonganHutang->sum();
            $SisaHutangData1 = $SisaHutangData->sum();
            
            $sisahutangTotal = $SisaHutangData1 - $totalPotonganHutang;

            if ($sisahutangTotal === 0) {
                // Update data di database
                LoanModel::where('employee_id', $nik)
                    ->where('is_paid', 0)
                    ->update(['is_paid' => 1, 'remaining_amount' => 0]);
            }else{
                LoanModel::where('employee_id', $nik)
                    ->where('is_paid', 0)
                    ->update(['is_paid' => 0, 'remaining_amount' => $sisahutangTotal]);
            }

            // Garda Pratama
            $potonganGP = Gp::where('employee_id', $nik)
            ->where('is_paid', 0)
            ->pluck('installment_amount');

            $sisaGp = Gp::where('employee_id', $nik)
                ->where('is_paid', 0)
                ->pluck('remaining_amount');
            
            $TotalGP = $potonganGP->sum();
            $SisaGP = $sisaGp->sum();
            
            $SistaTotalGP = $SisaGP - $TotalGP;

            if ($SistaTotalGP === 0) {
                // Update data di database
                Gp::where('employee_id', $nik)
                    ->where('is_paid', 0)
                    ->update(['is_paid' => 1, 'remaining_amount' => 0]);
            }else{
                Gp::where('employee_id', $nik)
                    ->where('is_paid', 0)
                    ->update(['is_paid' => 0, 'remaining_amount' => $SistaTotalGP]);
            }

            // Mengakumulasi jumlah hari dan total gaji dari setiap absensi
            foreach ($absen as $absensi) {

                $tanggalAbsensi = \Carbon\Carbon::createFromFormat('Y-m-d', $absensi->tanggal);
                $inPeriode = $tanggalAbsensi->isBetween($startDate, $endDate);

                if ($inPeriode) {
                    // Jika absensi berada dalam periode, tambahkan jumlah hari
                    $totalHari++;
                }

                if (!empty($absensi->project_backup)) {
                    // Jika ada project_backup, tambahkan jumlah hari backup
                    $totalHariBackup++;
                }

                // Ambil ID proyek dari kolom project dan project_backup
                $projectIds = [$absensi->project];
                $projectBackup = [$absensi->project_backup];

                // Dapatkan data project details dan rate harian dari project
                $rate_harian = 0;
                if (!empty($projectIds)) {
                    $projectDetails = ProjectDetails::whereIn('project_code', $projectIds)
                        ->where('jabatan', $jabatan)
                        ->pluck('rate_harian', 'project_code');

                    foreach ($projectIds as $projectId) {
                        if (isset($projectDetails[$projectId])) {
                            // Jika absensi berada dalam periode, tambahkan gaji
                            $rate_harian = $projectDetails[$projectId];
                            $totalGaji += $inPeriode ? $projectDetails[$projectId] : 0;
                        }
                    }
                }
                
                // backup rate
                $rate_harianbackup = 0;
                if (!empty($projectBackup)) {
                    $projectDetailsBackup = ProjectDetails::whereIn('project_code', $projectBackup)
                        ->where('jabatan', $jabatan)
                        ->pluck('rate_harian', 'project_code');

                    foreach ($projectBackup as $projectIdBackup) {
                        if (isset($projectDetailsBackup[$projectIdBackup])) {
                            // Jika absensi berada dalam periode, tambahkan gaji backup
                            $rate_harianbackup = $projectDetailsBackup[$projectIdBackup];
                            $totalGajiBackup += $inPeriode ? $projectDetailsBackup[$projectIdBackup] : 0;
                        }
                    }
                }
                // Allowence 
                $ProjectAllowances = ProjectDetails::whereIn('project_code', $projectIds)
                    ->where('jabatan', $jabatan)
                    ->select('p_bpjs_ks', 'p_tkerja', 'p_tlain')
                    ->get();

                $projectAllowancesTotal = 0;
                    foreach ($ProjectAllowances as $projectDetailallowence) {
                        $projectAllowancesTotal += array_sum($projectDetailallowence->toArray());
                    }

                // Deducitons
                $ProjectDeduction = ProjectDetails::whereIn('project_code', $projectIds)
                    ->where('jabatan', $jabatan)
                    ->select('p_bpjstk', 'p_tseragam', 'p_operasional')
                    ->get();

                $dataPengurangBPJS = ProjectDetails::whereIn('project_code', $projectIds)
                    ->where('jabatan', $jabatan)
                    ->pluck('p_bpjstk')
                    ->first();

                $projectDedutionsTotal = 0;
                foreach ($ProjectDeduction as $projectDetaildeductions) {
                    $projectDedutionsTotal += array_sum($projectDetaildeductions->toArray());
                }

                // Perhitungan PPH21
                $PenghasilanBruto = $totalGaji + $projectAllowancesTotal + $totalGajiBackup;
                $penghasilanNeto = $PenghasilanBruto - $dataPengurangBPJS;
                $totalNeto = $penghasilanNeto*12;
                $dataPTKP = $totalNeto -  54000000;
                // Penghasilan Kena Pajak Setahun / Disetahunkan
                $persentasePTKP = 0.05;
                $dataSetahun = $dataPTKP * $persentasePTKP;

                $totalPPH = $dataSetahun / 12;

                if($totalPPH <= 0) {
                    $totalPPH = 0;
                }else{
                    $totalPPH = $totalPPH;
                }
                
                $allowenceData = [
                    'totalHari' => $totalHari,
                    'totalHariBackup' => $totalHariBackup,
                    'totalGaji' => $totalGaji,
                    'totalGajiBackup' => $totalGajiBackup,
                    'rate_harian' => $rate_harian,
                    'rate_harian_backup' => $rate_harianbackup,
                    'allowence_total' => $projectAllowancesTotal,
                    'projectAllowances' => $ProjectAllowances->toArray(),
                    
                ];

                $deductiondata = [
                    'projectDeductions' => $ProjectDeduction->toArray(),
                    'deductions_total' => $projectDedutionsTotal + $totalPotonganHutang,
                    'potongan_hutang' => $totalPotonganHutang,
                    'potongan_gp' => $TotalGP,
                    'PPH21' => $totalPPH,
                ];
                $dataDeduction = $projectDedutionsTotal + $totalPotonganHutang + $TotalGP;

                // THP
                $thp = $totalGaji + $totalGajiBackup + $projectAllowancesTotal - $dataDeduction - $totalPPH;

                $allowenceData = json_encode($allowenceData);
                $deductionData = json_encode($deductiondata);
            }

            // Simpan data ke tabel payroll
            $payroll = new Payroll();
            $payroll->employee_code = $nik;
            $payroll->periode = $request->periode;
            $payroll->thp = $thp;
            $payroll->allowences = $allowenceData;
            $payroll->deductions = $deductionData;
            $payroll->save();
        }

        // Redirect atau tampilkan pesan sukses
        return redirect()->route('payroll-kas.index')->with('success', 'Data payroll berhasil disimpan.');
    }

    public function getEmployees(Request $request) {
        $unitBisnis = $request->input('unit_bisnis');
        // Ambil daftar karyawan berdasarkan unit bisnis
        $employees = Employee::where('unit_bisnis', $unitBisnis)
                     ->where('organisasi', 'Frontline Officer') 
                     ->get();

        return response()->json(['employees' => $employees]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $payroll = Payroll::findOrFail($id);
    
            // Jika data ditemukan, tampilkan halaman detail payroll
            return view('pages.hc.kas.payroll.show', ['payroll' => $payroll]);
        } catch (\Exception $e) {
            // Jika data tidak ditemukan, tampilkan pesan kesalahan atau redirect ke halaman lain
            return back()->with('error', 'Data payroll tidak ditemukan.');
        }
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
