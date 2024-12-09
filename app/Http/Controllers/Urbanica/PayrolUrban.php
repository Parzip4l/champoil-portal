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

                
                
                // Initialize arrays to store additional data
                $additionalDataArray = [];
                $deductionDataArray = [];
                $additionalAllowanceTotal = 0;
                $additionalDeductionTotal = 0;
                $totalLembur = 0;
                $absenData = 0;
                $totalGajiAbsen = 0;

                $absendata = Absen::where('nik', $code)->whereBetween('tanggal',[$startDateFormatted,$endDateFormatted])->count();
                // Loop through additional allowances
                foreach ($additionalAllowances as $additionalData) {
                    $componentName = $additionalData->component_name;
                    $nominal = $additionalData->nominal;
                    // Add additional data to the array
                    $additionalAllowanceTotal += $nominal;
                    $additionalDataArray[$componentName] = $nominal;
                }

                // Loop through additional deductions
                foreach ($additionalDeductions as $additionalDataDeductions) {
                    $componentName = $additionalDataDeductions->component_name;
                    $nominal = $additionalDataDeductions->nominal;
                    // Add additional data to the array
                    $additionalDeductionTotal += $nominal;
                    $deductionDataArray[$componentName] = $nominal;
                }
                
                
                if ($payrollComponents) {
                    // Get payroll component details
                    $basic_salary = $payrollComponents->daily_salary;
                    $allowancesData = $payrollComponents->allowances;
                    $deductionsData = $payrollComponents->deductions;
                    $NetSalary = $payrollComponents->net_salary;
                    $allowancesArray = json_decode($allowancesData, true);
                    $deductionArray = json_decode($deductionsData, true);
                    
                    $totalGajiAbsen = $basic_salary * $absendata;
                    $lemburRate = isset($allowancesArray['lembur'][0]) ? (float) $allowancesArray['lembur'][0] : 0;
                
                    // Get overtime hours
                    $ceklembur = RequestAbsen::where('employee', $code)
                        ->where('status', 'L')
                        ->where('aprrove_status', 'Approved')
                        ->whereBetween('tanggal', [$startDate, $endDate])
                        ->select('employee', DB::raw('SUM(jam_lembur) as total_lembur'))
                        ->groupBy('employee')
                        ->first();
                    
                    $totalLembur = $ceklembur ? $ceklembur->total_lembur * $lemburRate : 0;
                    $totalJamLembur = $ceklembur ? $ceklembur->total_lembur : 0;
                
                    // Add total overtime hours to allowances
                    if (!isset($allowancesArray['total_overtime_hours'])) {
                        $allowancesArray['total_overtime_hours'] = 0;
                    }
                    $allowancesArray['total_overtime_hours'] += $totalJamLembur;

                    if (!isset($allowancesArray['total_overtime_pay'])) {
                        $allowancesArray['total_overtime_pay'] = 0;
                    }
                    $allowancesArray['total_overtime_pay'] += $totalLembur;
                
                    // Add total absence to allowances
                    if (!isset($allowancesArray['total_absence'])) {
                        $allowancesArray['total_absence'] = 0;
                    }
                    $allowancesArray['total_absence'] += $absendata;  // Assuming $absendata contains the number of absence days
                
                    // Ensure 'total_allowance' and 'total_deduction' are initialized as arrays
                    if (!is_array($allowancesArray)) {
                        $allowancesArray = ['total_allowance' => 0];
                    } else if (!isset($allowancesArray['total_allowance'])) {
                        $allowancesArray['total_allowance'] = 0;
                    }
                
                    if (!is_array($deductionArray)) {
                        $deductionArray = ['total_deduction' => 0];
                    } else if (!isset($deductionArray['total_deduction'])) {
                        $deductionArray['total_deduction'] = 0;
                    }
                
                    // Add additional totals to existing totals
                    $allowancesArray['total_allowance'] += $additionalAllowanceTotal + $totalLembur;
                    $deductionArray['total_deduction'] += $additionalDeductionTotal;
                
                    // Merge additional data with existing arrays
                    $allowancesArray = array_merge($allowancesArray, $additionalDataArray);
                    $deductionArray = array_merge($deductionArray, $deductionDataArray);
                    
                    // Convert arrays back to JSON
                    $newAllowancesData = json_encode($allowancesArray);
                    $newDeductionsData = json_encode($deductionArray);
                    // Calculate Net Salary
                    $netSalary = round($totalGajiAbsen + $totalLembur + $additionalAllowanceTotal - $additionalDeductionTotal);
                    // Save payroll data
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
