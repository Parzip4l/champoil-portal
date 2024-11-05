<?php

namespace App\Http\Controllers\CgControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use App\ModelCG\Payroll;
use App\Employee;
use App\Loan\LoanModel;
Use App\GardaPratama\Gp;
use App\Absen;
use App\ModelCG\Schedule;
use App\ModelCG\ScheduleBackup;
use App\ModelCG\Project;
use App\ModelCG\ProjectDetails;
use App\Koperasi\LoanPayment;
use App\Koperasi\Koperasi;
use App\Koperasi\Anggota;
use App\Koperasi\Loan;
use App\Koperasi\Saving;

// TaX
use App\Pajak\Pajak;
use App\Pajak\PajakDetails;

// Additional Component
use App\Component\ComponentMaster;
use App\Component\ComponentDetails;

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
        $start_date = $today->day >= 20 ? $today->copy()->day(20) : $today->copy()->subMonth()->day(21);
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
        $code = Auth::user()->employee_code;
        $dataLogin = Employee::where('nik', $code)->first();

        // Validate request
        $request->validate([
            'month' => 'required',
            'year' => 'required',
            'periode' => 'required',
            'unit_bisnis' => 'required',
        ]);
        $month = $request->month;
        $year = $request->year;
        $monthsMap = [
            "Januari" => 1,
            "Februari" => 2,
            "Maret" => 3,
            "April" => 4,
            "Mei" => 5,
            "Juni" => 6,
            "Juli" => 7,
            "Agustus" => 8,
            "September" => 9,
            "Oktober" => 10,
            "November" => 11,
            "Desember" => 12
        ];
        $monthNumber = $monthsMap[$month] ?? null;
        $startDate = Carbon::create($year, $monthNumber, 21);
        if ($monthNumber == 12) {
            // Handle December case, where end date should be in the next year
            $endDate = Carbon::create($year + 1, 1, 20);
        } else {
            $endDate = Carbon::create($year, $monthNumber + 1, 20);
        }
       
        // Split Periode Data
        $periodeDates = explode(' - ', $request->periode);
        $startDate->format('Y-m-d');
        $endDate->format('Y-m-d');

        DB::beginTransaction();
        try {
            $employeeCodes = is_array($request->employee_code) ? $request->employee_code : [$request->employee_code];
            $existingPayrolls = [];

            foreach ($employeeCodes as $nik) {
                // Get employee data
                $existingPayroll = Payroll::where('employee_code', $nik)
                ->where('periode', $startDate->format('d-m-Y') . ' - ' . $endDate->format('d-m-Y'))
                ->first();

                if ($existingPayroll) {
                    $employee = Employee::where('nik', $nik)->first();
                    $existingPayrolls[] = $employee->nama;
                    continue; // Skip saving for this employee
                }

                $employee = Employee::where('nik', $nik)->first();
                if (!$employee) {
                    continue; // Jika karyawan tidak ditemukan, lanjutkan ke karyawan berikutnya
                }
                $jabatan = $employee->jabatan;
                $taxCode = $employee->tax_code;
                // Get attendance data based on the period and employee's NIK
                $absen = Absen::where('nik', $nik)
                    ->whereBetween('tanggal', [$startDate, $endDate])
                    ->get();
                $totalWorkingDays = $absen->count();

                // Initialize variables
                $totalHari = 0;
                $totalGaji = 0;
                $TotalHariBackup = 0;
                $totalGajiBackup = 0;
                $totalPotonganHutang = 0;
                $TotalGP = 0;
                $thp = 0;
                $allowenceData = [];
                $deductionData = [];
                $montlySalary = 0;
                $rate_harian = 0;
                $rate_harianbackup = 0;
                $potonganAbsen = 0;
                $totalSimpanan = 0;

                // Koperasi Deduction
                $anggota = Anggota::where('employee_code', $nik)->first();

                // Initialize variables for contributions and loan deductions
                $nominalSimpananWajib = 0;
                $loanDeductions = 0;

                if ($anggota) {
                    // Retrieve cooperative membership details
                    $koperasi = Koperasi::where('company', $dataLogin->unit_bisnis)->first();
                    
                    if ($koperasi) {
                        // Retrieve the mandatory contribution from the Koperasi table
                        $nominalSimpananWajib = $koperasi->iuran;
                    }

                    $saving = Saving::where('employee_id', $nik)->get();
                    $totalSimpanan = $saving->sum('jumlah_simpanan');

                    // Check if the employee has an 'onloan' status
                    if ($anggota->loan_status === 'onloan') {
                        // Retrieve all approved loans for the employee
                        $loans = Loan::where('employee_code', $nik)
                            ->where('status', 'approve')
                            ->get();
                        
                        $allLoansPaidOff = true;
                        
                        foreach ($loans as $loan) {
                            $sisaHutangSaya =  LoanPayment::where('loan_id', $loan->id)->select('sisahutang')->orderBy('created_at', 'desc')
                            ->first();
                           
                            // Calculate the remaining amount to be paid
                            if ($sisaHutangSaya) {
                                // Calculate the remaining amount to be paid
                                $remainingAmount = $sisaHutangSaya->sisahutang - $loan->instalment;
                
                                // Only record the payment if there's remaining debt
                                if ($sisaHutangSaya->sisahutang > 0) {
                                    $loanPayment = new LoanPayment();
                                    $loanPayment->loan_id = $loan->id;
                                    $loanPayment->employee_code = $anggota->employee_code;
                                    $loanPayment->tanggal_pembayaran = Carbon::now();
                                    $loanPayment->jumlah_pembayaran = $loan->instalment;
                                    $loanPayment->sisahutang = max($remainingAmount, 0); // Ensure sisahutang is not negative
                                    $loanPayment->save();

                                    $anggota->sisahutang = max($remainingAmount, 0);
                                    $anggota->save();
                                    
                                }
                                if ($remainingAmount > 0) {
                                    $allLoansPaidOff = false;
                                }
                            }
                        }

                        if ($allLoansPaidOff) {
                            $anggota->loan_status = 'noloan';
                            $anggota->save();
                        }

                        $anggota->saldosimpanan = $totalSimpanan + $nominalSimpananWajib;
                        $anggota->save();
                        // Calculate the total loan deductions
                        $loanDeductions = $loans->sum('instalment');
                    }
                }

                $newSaving = new Saving();
                $newSaving->employee_id = $nik;
                $newSaving->tanggal_simpan = Carbon::now();
                $newSaving->jumlah_simpanan = $nominalSimpananWajib;
                $newSaving->totalsimpanan = $totalSimpanan + $nominalSimpananWajib;
                $newSaving->save();
                
                // Loan deduction
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
                    LoanModel::where('employee_id', $nik)
                        ->where('is_paid', 0)
                        ->update(['is_paid' => 1, 'remaining_amount' => 0]);
                } else {
                    LoanModel::where('employee_id', $nik)
                        ->where('is_paid', 0)
                        ->update(['is_paid' => 0, 'remaining_amount' => $sisahutangTotal]);
                }

                // Additional Component
                $componentDetails  = ComponentDetails::where('employee_code',$nik)->get();
                $allowanceTotalAdditional=0;
                $deductionTotalAdditional=0;
                foreach ($componentDetails as $dataAdditional) {
                    // Fetch effective date from ComponentMaster using code_master
                    $componentMaster = ComponentMaster::where('code', $dataAdditional->code_master)->first();
                
                    if ($componentMaster) {
                        $effectiveDate = $componentMaster->effective_date;
                
                        // Check if the effective date is today or in the future
                        if ($effectiveDate >= Carbon::today()) {
                            $typeData = $dataAdditional->type;
                
                            if ($typeData === 'Deductions') {
                                $deductionTotalAdditional += intval($dataAdditional->nominal);
                            } elseif ($typeData === 'Allowences') {
                                $allowanceTotalAdditional += intval($dataAdditional->nominal);
                            }
                        }
                    }
                }
                
                
                // GP deduction
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
                    Gp::where('employee_id', $nik)
                        ->where('is_paid', 0)
                        ->update(['is_paid' => 1, 'remaining_amount' => 0]);
                } else {
                    Gp::where('employee_id', $nik)
                        ->where('is_paid', 0)
                        ->update(['is_paid' => 0, 'remaining_amount' => $SistaTotalGP]);
                }

                // Backup calculation
                $projectBackup = ScheduleBackup::where('employee', $nik)
                    ->whereBetween('tanggal', [$startDate, $endDate])
                    ->select('project', 'employee', 'man_backup')
                    ->get();
                // dd($projectBackup);
                $backupTotals = [];
                foreach ($projectBackup as $backupData) {
                    $manBackup = $backupData->man_backup;
                    $projectIDBackup = $backupData->project;
                    $totalHariBackupmentah = 0;
                    $man_backup_jabatan = Employee::where('nik',$manBackup)->first();
                    // Determine daily rate
                    $schedulesManBackup = Schedule::where('employee', $manBackup)
                        ->whereBetween('tanggal', [date('Y-m-d',strtotime($startDate)), date('Y-m-d',strtotime($endDate))])
                        ->where('shift', '!=', 'OFF')
                        ->get();
                    $totalScheduleManBackup = $schedulesManBackup->count();
                    $jabatanManbackup = 
                    $projectDetailsBackupData = ProjectDetails::where('project_code', $projectIDBackup)
                        ->where('jabatan', $man_backup_jabatan->jabatan)
                        ->pluck('tp_bulanan', 'project_code');
                    
                    $monthlySalaryProject = $projectDetailsBackupData->sum();
                    $rateHarianBackup = $monthlySalaryProject / $totalScheduleManBackup;
                    $totalHariBackupmentah = 1;
                    $totalGajiBackupmentah = $totalHariBackupmentah * $rateHarianBackup;
                    
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

                // Accumulate total days and salary from each attendance
                foreach ($absen as $absensi) {
                    $tanggalAbsensi = \Carbon\Carbon::createFromFormat('Y-m-d', $absensi->tanggal);
                    $inPeriode = $tanggalAbsensi->isBetween($startDate, $endDate);

                    if ($inPeriode) {
                        $totalHari++;
                    }

                    $rate_potongan  = 0;
                    $schedules = Schedule::where('employee', $nik)
                        ->whereBetween('tanggal', [date('Y-m-d',strtotime($startDate)), date('Y-m-d',strtotime($endDate))])
                        ->where('shift', '!=', 'OFF')
                        ->get();
                    $totalDaysInSchedules = $schedules->count() + 1;
                    $tidakmasukkerja = 0;

                    if ($totalDaysInSchedules > 0) {
                        $rate_potongan = round($totalGaji / $totalDaysInSchedules);
                    }

                    if ($totalHari < $totalDaysInSchedules) {
                        $potonganAbsen = $rate_potongan * ($totalDaysInSchedules - $totalWorkingDays);
                        $tidakmasukkerja = $totalDaysInSchedules - $totalWorkingDays;
                    }

                    $projectIds = [$absensi->project];
                    $rate_harian = 0;
                    if (!empty($projectIds)) {
                        $projectDetails = ProjectDetails::whereIn('project_code', $projectIds)
                            ->where('jabatan', $jabatan)
                            ->pluck('tp_bulanan', 'project_code');
                        $totalGaji = $projectDetails->sum();
                        $montlySalary = $projectDetails->sum();
                        
                        $projectDetailsPPH = ProjectDetails::whereIn('project_code', $projectIds)
                            ->where('jabatan', $jabatan)
                            ->select('p_gajipokok', 'p_tkerja', 'p_tlain','p_bpjs_ks')
                            ->get();
                            
                        $p_gajipokok = 0;
                        $p_tkerja = 0;
                        $p_tlain = 0;

                        if ($projectDetailsPPH->isNotEmpty()) {
                            $p_gajipokok = $projectDetailsPPH->sum('p_gajipokok');
                            $p_tkerja = $projectDetailsPPH->sum('p_tkerja');
                            $p_tkes = $projectDetailsPPH->sum('p_bpjs_ks');
                            $p_tlain = $projectDetailsPPH->sum('p_tlain');
                        }

                        $gajiPPH = ($p_gajipokok + $p_tkerja + $p_tlain + $p_tkes + 61300 - $potonganAbsen) + $totalGajiBackup;

                        $dataPajak = PajakDetails::where('pajak_id', $taxCode)->get();
                        $matchingPajakDetail = null;

                        foreach ($dataPajak as $pajakDetail) {
                            if ($gajiPPH >= $pajakDetail->min_bruto && $gajiPPH <= $pajakDetail->max_bruto) {
                                $matchingPajakDetail = $pajakDetail;
                                break;
                            }
                        }

                        if ($matchingPajakDetail) {
                            // Extract and convert the persentase to a float
                            $persentaseStr = $matchingPajakDetail->persentase;
                            $persentaseFloat = (float) str_replace(',', '.', $persentaseStr);

                            // Calculate the percentage of gajiPPH
                            $PajakPendapatan = round($gajiPPH * $persentaseFloat);

                            // $PajakPendapatan = $gajiPPH;
                        } else {
                            // Handle the case where no matching tax detail is found
                            $PajakPendapatan = 0;
                        }
                        
                    }

                    

                    $ProjectAllowances = ProjectDetails::whereIn('project_code', $projectIds)
                        ->where('jabatan', $jabatan)
                        ->select('p_tkerja','p_tlain')
                        ->get();

                    $tunjanganLain = $ProjectAllowances->sum('p_tlain');
                    
                    $projectAllowancesTotal = 0;
                    foreach ($ProjectAllowances as $projectDetailallowence) {
                        $projectAllowancesTotal += array_sum($projectDetailallowence->toArray());
                    }
                    
                    $ProjectDeduction = ProjectDetails::whereIn('project_code', $projectIds)
                        ->where('jabatan', $jabatan)
                        ->select('p_bpjs_ks')
                        ->get();
                        
                    $projectDeductionTotal = 0;
                    foreach ($ProjectDeduction as $projectDetailDeduction) {
                        $projectDeductionTotal += array_sum($projectDetailDeduction->toArray());
                    }

                    $potonganlain = $tidakmasukkerja + $TotalGP;
                    $montlySalary = $totalGaji + $totalGajiBackup;
                    $thp = $montlySalary - ($potonganlain + $totalPotonganHutang + $nominalSimpananWajib + $loanDeductions + $potonganAbsen + $PajakPendapatan + $deductionTotalAdditional - $allowanceTotalAdditional);
                    $totalDeduction = $potonganAbsen + $totalPotonganHutang + $TotalGP + $potonganlain + $nominalSimpananWajib + $loanDeductions;
                    $allowanceTotal = $projectAllowancesTotal + $totalGajiBackup;
                    $allowenceData = [
                        'tunjangan_lain' => $projectAllowancesTotal,
                        'allowance_total' => $allowanceTotal,
                        'totalHariSchedule' => $totalDaysInSchedules,
                        'totalHari' => $totalWorkingDays,
                        'totalHariBackup' => $TotalHariBackup,
                        'totalGajiBackup' => $totalGajiBackup,
                    ];

                    $deductionData = [
                        'potongan_absen' => $potonganAbsen,
                        'potongan_hutang' => $totalPotonganHutang,
                        'potongan_Gp' => $TotalGP,
                        'potongan_lain' => $potonganlain,
                        'PPH21' => $PajakPendapatan,
                        'tidak_masuk_kerja' => $tidakmasukkerja,
                        'iuran_koperasi' => $nominalSimpananWajib,
                        'hutang_koperasi' => $loanDeductions,
                        'total_deduction' => $totalDeduction,
                        'tidak_absen' =>$tidakmasukkerja,
                        'rate_harian' => $rate_potongan,
                    ];
                }

                
                $payroll = new Payroll();
                $payroll->employee_code = $nik;
                $payroll->periode = $startDate->format('d-m-Y') . ' - ' . $endDate->format('d-m-Y');
                $payroll->basic_salary = $montlySalary;
                $payroll->thp = $thp;
                $payroll->allowences = json_encode(array_merge($allowenceData, ['additional_allowances' => $allowanceTotalAdditional]));
                $payroll->deductions = json_encode(array_merge($deductionData, ['additional_deductions' => $deductionTotalAdditional]));
                $payroll->payrol_status = 'Unlocked';
                $payroll->payslip_status = 'Unpublish';
                $payroll->run_by = $dataLogin->nama;
                $payroll->save();
            }
            $message='';
            DB::commit();
            if (!empty($existingPayrolls)) {
                $message .= ' Namun, data payroll untuk karyawan berikut sudah ada dan tidak disimpan: ' . implode(', ', $existingPayrolls);
            }   

            return redirect()->route('payroll-kas.index')->with('success', 'Data payroll berhasil disimpan.'. $message);
        } catch (\Exception $e) {
            DB::rollBack();
            // Display the error message
            return redirect()->route('payroll-kas.index')
                             ->with('error', 'Terjadi kesalahan saat menyimpan data payroll. Error: ' . $e->getMessage());
        }
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
