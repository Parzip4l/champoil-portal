<?php

namespace App\Http\Controllers\CgControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\ModelCG\Payroll;
use App\Employee;
use App\Loan\LoanModel;
Use App\GardaPratama\Gp;
use App\Absen;
use App\ModelCG\Schedule;
use App\ModelCG\ScheduleBackup;
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

        // Pecah Data Periode
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
            $totalWorkingDays = count($absen);

            // Deklarasi Variable
            $totalHari = 0;
            $totalGaji = 0;
            $TotalHariBackup = 0;
            $totalGajiBackup = 0;
            $allowenceData= [];
            $deductiondata = [];
            $totalPotonganHutang = 0;
            $TotalGP = 0;
            $thp = 0;
            $allowenceData = null;
            $deductionData = null;
            $montlySalary = 0;
            $rate_harian = 0;
            $rate_harianbackup = 0;

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
            // End Potongan Hutang

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
            // End Perhitunga GP

            // Hitungan Backup

                // Ambil Project Backup
                $projectBackup = ScheduleBackup::where('employee',$nik)
                                ->whereBetween('tanggal', [$startDate, $endDate])
                                ->select('project','employee','man_backup')
                                ->get();

                $backupTotals = [];

                foreach ($projectBackup as $backupData) {
                    $manBackup = $backupData->man_backup;
                    $projectIDBackup = $backupData->project;
                    $totalHariBackupmentah = 0;
                
                    // Tentukan Rate Harian
                    $schedulesManBackup = Schedule::where('employee', $manBackup)
                        ->whereBetween('tanggal', [$startDate, $endDate])
                        ->where('shift', '!=', 'Off')
                        ->get();
                
                    $totalScheduleManBackup = $schedulesManBackup->count();
                
                    $projectDetailsBackupData = ProjectDetails::where('project_code', $projectIDBackup)
                        ->where('jabatan', $jabatan)
                        ->pluck('tp_bulanan', 'project_code');
                
                    $monthlySalaryProject = $projectDetailsBackupData->sum();
                    $rateHarianBackup = round($monthlySalaryProject / $totalScheduleManBackup);
                    $totalHariBackupmentah = 1;
                    $totalGajiBackupmentah = $totalHariBackupmentah  * $rateHarianBackup;
                
                    // Simpan total bayaran untuk setiap project backup dalam array
                    if (!isset($backupTotals[$projectIDBackup])) {
                        $backupTotals[$projectIDBackup] = [
                            'ProjectCodeBackup' => 0,
                            'totalGajiBackupmentah' => 0,
                            'rateHarianBackup' => 0,
                            'totalHariBackupmentah' => 0,
                        ];
                    }

                    $backupTotals[$projectIDBackup]['ProjectCodeBackup'] = $projectIDBackup;
                    $backupTotals[$projectIDBackup]['rateHarianBackup'] = $rateHarianBackup;
                    $backupTotals[$projectIDBackup]['totalHariBackupmentah'] += 1;
                    $backupTotals[$projectIDBackup]['totalGajiBackupmentah'] += $totalGajiBackupmentah;

                    if (!isset($totalGajiBackup)) {
                        $totalGajiBackup = 0;
                    }
                    $totalGajiBackup += $totalGajiBackupmentah;
                    $TotalHariBackup += $totalHariBackupmentah;
                }
                // Akhir perhitungan Backup

            // Mengakumulasi jumlah hari dan total gaji dari setiap absensi
            foreach ($absen as $absensi) {

                $tanggalAbsensi = \Carbon\Carbon::createFromFormat('Y-m-d', $absensi->tanggal);
                $inPeriode = $tanggalAbsensi->isBetween($startDate, $endDate);

                if ($inPeriode) {
                    // Jika absensi berada dalam periode, tambahkan jumlah hari
                    $totalHari++;
                }

                // Ambil ID proyek dari kolom project dan project_backup
                $projectIds = [$absensi->project];
                
                // Dapatkan data project details dan rate harian dari project Backup
                $rate_harian = 0;
                if (!empty($projectIds)) {
                    $projectDetails = ProjectDetails::whereIn('project_code', $projectIds)
                        ->where('jabatan', $jabatan)
                        ->pluck('tp_bulanan', 'project_code');
                        
                        $totalGaji = $projectDetails->sum();
                        $montlySalary = $projectDetails->sum();

                    $projectDetailsPPH = ProjectDetails::whereIn('project_code', $projectIds)
                        ->where('jabatan', $jabatan)
                        ->select('p_gajipokok', 'p_tkerja', 'p_tlain')
                        ->get();
                       
                    // Initialize variables to store each selected field
                    $p_gajipokok = 0;
                    $p_tkerja = 0;
                    $p_tlain = 0;

                    // Check if the collection is not empty
                    if ($projectDetailsPPH->isNotEmpty()) {
                        // Access the values using array keys
                        $p_gajipokok = $projectDetailsPPH->sum('p_gajipokok');
                        $p_tkerja = $projectDetailsPPH->sum('p_tkerja');
                        $p_tlain = $projectDetailsPPH->sum('p_tlain');
                    }

                    // Calculate the total salary
                    $gajiPPH = $p_gajipokok + $p_tkerja + $p_tlain;
                }

                // Rate Potongan
                $rate_potongan  = 0;
                // cek jumlah schedule
                $schedules = Schedule::where('employee', $nik)
                    ->whereBetween('tanggal', [$startDate, $endDate])
                    ->where('shift', '!=', 'Off')
                    ->get();

                $totalDaysInSchedules = $schedules->count();
                $tidakmasukkerja = 0;
                // Calculate rate_potongan
                if ($totalDaysInSchedules > 0) {
                    $rate_potongan = round($totalGaji / $totalDaysInSchedules);
                }
                
                if ($totalHari < $totalDaysInSchedules) {
                    $potonganAbsen = $rate_potongan * ($totalDaysInSchedules - $totalWorkingDays);
                    $tidakmasukkerja = $totalDaysInSchedules - $totalWorkingDays;
                }
                // End Potongan Daily

                // Allowence 
                $ProjectAllowances = ProjectDetails::whereIn('project_code', $projectIds)
                    ->where('jabatan', $jabatan)
                    ->select('p_bpjs_ks', 'p_tlain')
                    ->get();

                $bpjsMandiri = $ProjectAllowances->sum('p_bpjs_ks');
                $tunjanganLain = $ProjectAllowances->sum('p_tlain');

                $projectAllowancesTotal = 0;
                    foreach ($ProjectAllowances as $projectDetailallowence) {
                        $projectAllowancesTotal += array_sum($projectDetailallowence->toArray());
                    }
                // End Allowence

                // deductions
                $ProjectDeduction = ProjectDetails::whereIn('project_code', $projectIds)
                    ->where('jabatan', $jabatan)
                    ->select('p_bpjstk')
                    ->get();

                $dataPengurangBPJS = ProjectDetails::whereIn('project_code', $projectIds)
                    ->where('jabatan', $jabatan)
                    ->pluck('p_bpjstk')
                    ->first();

                $bpjs_tk = $dataPengurangBPJS;

                $projectDedutionsTotal = 0;
                foreach ($ProjectDeduction as $projectDetaildeductions) {
                    $projectDedutionsTotal += array_sum($projectDetaildeductions->toArray());
                }
                // End Deductions

                // Perhitungan PPH21
                $PenghasilanBruto = $gajiPPH + $bpjsMandiri + $totalGajiBackup + 61300 - $potonganAbsen;
                $biayaJabatan = $PenghasilanBruto * 0.05;

                $penghasilanNeto = $PenghasilanBruto - $biayaJabatan;
                
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
                // End PPH
                
                // Masukan data allowence ke json
                $allowenceData = [
                    'totalHari' => $totalHari,
                    'totalHariSchedule' => $totalDaysInSchedules,
                    'totalHariBackup' => $TotalHariBackup,
                    'totalGaji' => $totalGaji,
                    'totalGajiBackup' => $totalGajiBackup,
                    'rate_harian' => $rate_potongan,
                    'rate_harian_backup' => $rate_harianbackup,
                    'bpjs_mandiri' => $bpjsMandiri,
                    'tunjangan_lain' => $tunjanganLain,
                ];

                // Data Deduction   
                $deductiondata = [
                    'bpjs_tk' => $bpjs_tk,
                    'potongan_hutang' => $totalPotonganHutang,
                    'potongan_absen' => $potonganAbsen,
                    'tidak_absen' => $tidakmasukkerja,
                    'potongan_gp' => $TotalGP,
                    'PPH21' => $totalPPH,
                    'deductions_total' => $projectDedutionsTotal + $totalPotonganHutang,
                ];
                $dataDeduction = $projectDedutionsTotal + $totalPotonganHutang + $TotalGP;

                // THP
                $pendapatanBersih = $totalGaji + $totalGajiBackup + $bpjsMandiri + $tunjanganLain;
                $thp = $pendapatanBersih - $potonganAbsen - $totalPotonganHutang - $TotalGP - $totalPPH;

                $allowenceData = json_encode($allowenceData);
                $deductionData = json_encode($deductiondata);
               
            }
            // Simpan data ke tabel payroll
            $payroll = new Payroll();
            $payroll->employee_code = $nik;
            $payroll->periode = $request->periode;
            $payroll->basic_salary = $montlySalary;
            $payroll->thp = $thp;
            $payroll->allowences = $allowenceData;
            $payroll->deductions = $deductionData;
            $payroll->payrol_status = 'Unlocked';
            $payroll->payslip_status = 'Unpublish';
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
