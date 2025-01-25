<?php

namespace App\Http\Controllers\Urbanica;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use App\Employee;
use App\Absen;
use App\Absen\RequestAbsen;
use App\Urbanica\PayrolUrbanica;

// TaX
use App\Pajak\Pajak;
use App\Pajak\PajakDetails;

// Additional Component
use App\Component\ComponentMaster;
use App\Component\ComponentDetails;
use App\PayrolComponent_NS;

class PayrolUrban extends Controller
{
    public function index()
    {
        $code = Auth::user()->employee_code;
        $employee = Employee::where('nik', $code)->first();

        if ($employee) {
            $unit_bisnis = $employee->unit_bisnis;
        
            // Mengambil data Payroll berdasarkan unit bisnis dari tabel Employee
            $payrol = PayrolComponent_NS::join('karyawan', 'payrol_component_ns.employee_code', '=', 'karyawan.nik')
                ->where('karyawan.unit_bisnis', $unit_bisnis)
                ->where('karyawan.resign_status','0')
                ->get();
        } else {
            $payrol = [];
        }

        return view('pages.hc.urbanica.payroll.index', compact('payrol'));
    }

    public function store(Request $request)
    {
        $code = Auth::user()->employee_code;
        $employee = Employee::where('nik', $code)->first();

        $unit_bisnis = $employee->unit_bisnis;
        $employeeCodes = $request->input('employee_code');
        $bulan = $request->input('month');
        $tahun = $request->input('year');
        
        $monthNames = [
            'Januari' => 1,
            'Februari' => 2,
            'Maret' => 3,
            'April' => 4,
            'Mei' => 5,
            'Juni' => 6,
            'Juli' => 7,
            'Agustus' => 8,
            'September' => 9,
            'Oktober' => 10,
            'November' => 11,
            'Desember' => 12
        ];
        
        $selectedMonth = $monthNames[$bulan];
        
        // Mengatur startDate dan endDate berdasarkan bulan yang dipilih
        if ($selectedMonth === 1) {
            // Jika bulan adalah Januari, tahun bulan sebelumnya adalah tahun - 1
            $startDate = Carbon::createFromDate($tahun - 1, 12, 21); // 21 Desember tahun sebelumnya
            $endDate = Carbon::createFromDate($tahun, 1, 20); // 20 Januari tahun ini
        } else {
            // Bulan lainnya
            $startDate = Carbon::createFromDate($tahun, $selectedMonth - 1, 21); // 21 bulan sebelumnya
            $endDate = Carbon::createFromDate($tahun, $selectedMonth, 20); // 20 bulan yang dipilih
        }
        
        // Hasilkan tanggal dalam format yang diinginkan
        $startDateFormatted = $startDate->format('Y-m-d');
        $endDateFormatted = $endDate->format('Y-m-d');

        
        try {
            // Begin database transaction
            DB::beginTransaction();

            // Loop through each employee code
            foreach ($employeeCodes as $code) {
                $payrollComponents = PayrolComponent_NS::where('employee_code', $code)->first();
                $additionalAllowances = ComponentDetails::where('employee_code', $code)
                                        ->where('type', 'Allowances')
                                        ->get();
            
                $additionalDeductions = ComponentDetails::where('employee_code', $code)
                                        ->where('type', 'Deductions')
                                        ->get();
            
                $additionalDataArray = [];
                $deductionDataArray = [];
                $additionalAllowanceTotal = 0;
                $additionalDeductionTotal = 0;
                $totalLembur = 0;
                $absendata = 0;
                $totalGajiAbsen = 0;
            
                $absendata = Absen::where('nik', $code)
                    ->whereBetween('tanggal', [$startDateFormatted, $endDateFormatted])
                    ->count();
            
                foreach ($additionalAllowances as $additionalData) {
                    $componentName = $additionalData->component_name;
                    $nominal = $additionalData->nominal;
                    $additionalAllowanceTotal += $nominal;
                    $additionalDataArray[$componentName] = $nominal;
                }
            
                foreach ($additionalDeductions as $additionalDataDeductions) {
                    $componentName = $additionalDataDeductions->component_name;
                    $nominal = $additionalDataDeductions->nominal;
                    $additionalDeductionTotal += $nominal;
                    $deductionDataArray[$componentName] = $nominal;
                }
            
                if ($payrollComponents) {
                    $basic_salary = $payrollComponents->daily_salary;
                    $allowancesData = $payrollComponents->allowances;
                    $deductionsData = $payrollComponents->deductions;
                    $NetSalary = $payrollComponents->net_salary;
                    $allowancesArray = json_decode($allowancesData, true);
                    $deductionArray = json_decode($deductionsData, true);
            
                    $totalGajiAbsen = $basic_salary * $absendata;
                    $lemburRate = isset($allowancesArray['lembur'][0]) ? (float)$allowancesArray['lembur'][0] : 0;
            
                    $ceklembur = RequestAbsen::where('employee', $code)
                        ->where('status', 'L')
                        ->where('aprrove_status', 'Approved')
                        ->whereBetween('tanggal', [$startDate, $endDate])
                        ->select('employee', DB::raw('SUM(jam_lembur) as total_lembur'))
                        ->groupBy('employee')
                        ->first();
            
                    $totalLembur = $ceklembur ? $ceklembur->total_lembur * $lemburRate : 0;
                    $totalJamLembur = $ceklembur ? $ceklembur->total_lembur : 0;
            
                    $allowancesArray['total_overtime_hours'] = $totalJamLembur ?? 0;
                    $allowancesArray['total_overtime_pay'] = $totalLembur ?? 0;
                    $allowancesArray['total_absence'] = $absendata ?? 0;
            
                    // Hitung ulang total allowance
                    $totalAllowance = 0;

                    // Loop untuk menghitung total allowance
                    foreach ($allowancesArray as $key => $value) {
                        // Hanya tambahkan "kerajinan" dan "uang_makan" ke totalAllowance
                        if (in_array($key, ['kerajinan', 'uang_makan'])) {
                            if (is_array($value)) {
                                $totalAllowance += (float)($value[0] ?? 0);
                            } elseif (is_numeric($value)) {
                                $totalAllowance += (float)$value;
                            }
                        }
                    }

                    // Tambahkan total lembur ke total allowance
                    $totalAllowance += $totalLembur;

                    // Simpan total allowance ke dalam array allowancesArray
                    $allowancesArray['total_allowance'] = $totalAllowance;
            
                    // Hitung ulang total deduction
                    $totalDeduction = 0;
                    foreach ($deductionArray as $key => $value) {
                        if (is_array($value)) {
                            $totalDeduction += (float)($value[0] ?? 0);
                        } elseif (is_numeric($value)) {
                            $totalDeduction += (float)$value;
                        }
                    }
            
                    $deductionArray['total_deduction'] = $totalDeduction + $additionalDeductionTotal;
            
                    $allowancesArray = array_merge($allowancesArray, $additionalDataArray);
                    $deductionArray = array_merge($deductionArray, $deductionDataArray);
            
                    $newAllowancesData = json_encode($allowancesArray);
                    $newDeductionsData = json_encode($deductionArray);
            
                    $netSalary = round($totalGajiAbsen + $totalLembur + $allowancesArray['total_allowance'] - $deductionArray['total_deduction']);
            
                    $payroll = new PayrolUrbanica();
                    $payroll->employee_code = $code;
                    $payroll->periode = $startDateFormatted . ' - ' . $endDateFormatted;
                    $payroll->month = $bulan;
                    $payroll->year = $tahun;
                    $payroll->basic_salary = $totalGajiAbsen;
                    $payroll->allowances = $newAllowancesData;
                    $payroll->deductions = $newDeductionsData;
                    $payroll->thp = $netSalary;
                    $payroll->payrol_status = 'Unlocked';
                    $payroll->payslip_status = 'Unpublish';
                    $payroll->run_by = $employee->nama;
                    $payroll->save();
                }
            }
            
            // Commit the transaction
            DB::commit();

            return redirect()->route('payslip.showByMonth', ['month' => $bulan, 'year' => $tahun])->with('success', 'Payroll successfully created');
            
        } catch (\Exception $e) {
            // Rollback the transaction in case of any exception
            DB::rollback();
            // Log the error
            \Log::error('Error creating payroll: ' . $e->getMessage());
            // Redirect back with error message
            return redirect()->back()->with(['error' => 'Error creating payroll. Please try again.' . $e->getMessage()]);
        }
    }
}
