<?php

namespace App\Imports;

use App\Payrollns;
use App\Employee;
use App\PayrolComponent_NS;
use App\Absen;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Auth;

class PayrollImport implements ToCollection, WithHeadingRow
{
    protected $month;
    protected $week;
    protected $year;

    public function __construct($month, $week, $year)
    {
        $this->month = $month;
        $this->week = $week;
        $this->year = $year;
    }

    public function collection(Collection $rows)
    {
        $code = Auth::user()->employee_code;
        $employee = Employee::where('nik', $code)->first();
        foreach ($rows as $row) {
            $employeeCode = $row['employee_code'];
            $lembur_jam = $row['jam_lembur'];
            $uang_makan = $row['uang_makan'];
            $uang_kerajinan = $row['uang_kerajinan'];

            // Extract Week
            list($startDate, $endDate) = explode(' - ', $this->week);

            // Get payroll components
            $payrollComponents = PayrolComponent_NS::where('employee_code', $employeeCode)->first();
            if ($payrollComponents) {
                $allowancesData = json_decode($payrollComponents->allowances, true);
                $lemburAllowance = $allowancesData['lembur'][0] ?? 0;
                $jamLemburdata = $lembur_jam * $lemburAllowance;

                // Get total absences
                $totalAbsen = Absen::where('nik', $employeeCode)
                    ->whereBetween('tanggal', [$startDate, $endDate])
                    ->count();

                // Calculate total daily salary
                $daily_salary = $payrollComponents->daily_salary;
                $totaldaily = $totalAbsen * $daily_salary;

                // Calculate total deductions
                $dataDeductions = json_decode($payrollComponents->deductions, true);
                $totalPotongan = ($dataDeductions['hutang'][0] ?? 0) + 
                                 ($dataDeductions['mess'][0] ?? 0) + 
                                 ($dataDeductions['lain_lain'][0] ?? 0);

                // Calculate THP (Take Home Pay)
                $thpdetails = $totaldaily + $jamLemburdata + $uang_makan + $uang_kerajinan - $totalPotongan;

                // Save payroll data
                Payrollns::create([
                    'employee_code' => $employeeCode,
                    'month' => $this->month,
                    'year' => $this->year,
                    'periode' => $this->week,
                    'daily_salary' => $daily_salary,
                    'total_absen' => $totalAbsen,
                    'lembur_salary' => $lemburAllowance,
                    'jam_lembur' => $lembur_jam,
                    'total_lembur' => $jamLemburdata,
                    'uang_makan' => $uang_makan,
                    'uang_kerajinan' => $uang_kerajinan,
                    'potongan_hutang' => $dataDeductions['hutang'][0] ?? 0,
                    'potongan_mess' => $dataDeductions['mess'][0] ?? 0,
                    'potongan_lain' => $dataDeductions['lain_lain'][0] ?? 0,
                    'thp' => $thpdetails,
                    'total_daily' => $totaldaily,
                    'payrol_status' => 'Unlocked',
                    'payslip_status' => 'Unpublish',
                    'run_by' => $employee->nama,
                ]);
            }
        }
    }
}