<?php

namespace App\Http\Controllers\Payrol;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Employee;
use App\Payrol;
use App\ModelCG\Payroll;
use App\PayrolCM;
use App\Payrol\Component;
use App\Payrollns;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Route;

use App\Mail\PayslipEmail;
use PDF;

use App\Imports\PayrollImport;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;

class PayslipController extends Controller
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

        if ($employee) {
            $unit_bisnis = $employee->unit_bisnis;
                
            $data = Payrol::select('month', 'year','payrol_status','payslip_status','run_by', \DB::raw('SUM(net_salary) as total_net_salary'))
                    ->where('unit_bisnis',$unit_bisnis)
                    ->groupBy('month', 'year', 'payrol_status', 'payslip_status','run_by')
                    ->get();

            if ($unit_bisnis == 'Kas') {
                $dbSecondary = DB::connection('mysql_secondary');

                $datans = $dbSecondary->table('payrolls')
                    ->select('periode','payrol_status','payslip_status','run_by', \DB::raw('SUM(thp) as total_payroll')) // Tambahkan id pada select clause
                    ->groupBy('periode','payrol_status','payslip_status','run_by')
                    ->get();
            } else {
                $datans = Payrollns::select('month', 'year','periode','payrol_status','payslip_status','run_by',\DB::raw('SUM(thp) as total_payroll'))
                    ->groupBy('month', 'year','periode','payrol_status', 'payslip_status','run_by')
                    ->get();
            }

        } else {
            $data = [];
            $datans = [];
        }

        return view('pages.hc.payrol.payslip', compact('data', 'datans'));
    }

    public function showByMonth($month, $year)
    {
        $code = Auth::user()->employee_code;
        $employee = Employee::where('nik', $code)->first();

        if ($employee) {
            $unit_bisnis = $employee->unit_bisnis;
            $data = Payrol::join('karyawan', 'payrols.employee_code', '=', 'karyawan.nik')
                ->select('payrols.id', 'payrols.*')
                ->where('karyawan.unit_bisnis', $unit_bisnis)
                ->where('payrols.month', $month)
                ->where('payrols.year', $year)
                ->get();
        } else {
            $data = [];
        }
        
        return view('pages.hc.payrol.payslipbymonth', compact('data','month'));
    }

    public function showByPeriode($periode)
    {
        $code = Auth::user()->employee_code;
        $employee = Employee::where('nik', $code)->first();

        if ($employee) {
            $unit_bisnis = $employee->unit_bisnis;
            if ($unit_bisnis == 'Kas') {
                $dbSecondary = DB::connection('mysql_secondary');
                $dbMain = DB::connection('mysql');
                $datans = $dbSecondary->table('payrolls')
                    ->join($dbMain->getDatabaseName() . '.karyawan', 'payrolls.employee_code', '=', 'karyawan.nik')
                    ->select('payrolls.id', 'payrolls.*') 
                    ->where('karyawan.unit_bisnis', $unit_bisnis)
                    ->get();
            }else{
                $datans = Payrollns::join('karyawan', 'payrollns.employee_code', '=', 'karyawan.nik')
                            ->select('payrollns.id', 'payrollns.*') 
                            ->where('karyawan.unit_bisnis', $unit_bisnis)
                            ->where('payrollns.periode', $periode)
                            ->get();
            }
        } else {
            $datans = [];
        }
        
        return view('pages.hc.payrol.payrolbyperiode', compact('datans','periode'));
    }

    // Locked Payroll
    public function lockPayroll($month, $year)
    {
        $code = Auth::user()->employee_code;
        $employee = Employee::where('nik', $code)->first();
        $companyData = $employee->unit_bisnis;

        // Initialize an array to store PDF paths
        $pdfPaths = [];

        try {
            // Begin database transaction
            DB::beginTransaction();

            // Update payroll status to 'Locked'
            Payrol::where('month', $month)
                ->where('year', $year)
                ->where('unit_bisnis', $companyData)
                ->update(['payrol_status' => 'Locked']);

            // Fetch all employees in the payroll
            $payrolls = Payrol::where('month', $month)
                ->where('year', $year)
                ->where('unit_bisnis', $companyData)
                ->get();

            // Generate PDFs for each employee
            foreach ($payrolls as $payroll) {
                $employee = Employee::where('nik', $payroll->employee_code)->first();

                if ($employee) {
                    $directory = storage_path('app/public/payslips/');
                    if (!File::exists($directory)) {
                        File::makeDirectory($directory, 0755, true);
                    }

                    $pdfPath = $directory . 'payslip_' . $payroll->employee_code . '.pdf';
                    $pdf = PDF::loadView('pdf.payslip', compact('payroll', 'employee'));
                    $pdf->save($pdfPath);

                    $pdfPaths[$payroll->employee_code] = $pdfPath; // Store the path in the array
                }
            }

            // Send the email with the payslip PDF attachment to each employee
            foreach ($payrolls as $payroll) {
                $employee = Employee::where('nik', $payroll->employee_code)->first();
                if ($employee) {
                    $pdfPath = $pdfPaths[$payroll->employee_code] ?? null;
                    if ($pdfPath) {
                        Mail::to($employee->email)->send(new PayslipEmail($employee, $pdfPath));
                    }
                }
            }

            // Commit the transaction
            DB::commit();

            return redirect()->back()->with('success', 'Payroll locked and emails sent successfully');
        } catch (\Exception $e) {
            // Rollback the transaction in case of any exception
            DB::rollback();
            // Log the error
            \Log::error('Error locking payroll: ' . $e->getMessage());
            return redirect()->back()->with(['error' => 'Error locking payroll. Please try again.' . $e->getMessage()]);
        }
    }


    public function publishPayslip($month, $year)
    {
        // Check User Login
        $code = Auth::user()->employee_code;
        $employee = Employee::where('nik', $code)->first();
        $companyData = $employee->unit_bisnis;

        // Check if the payroll is locked
        $isPayrollLocked = Payrol::where('month', $month)->where('year', $year)->where('payrol_status', 'Locked')->exists();

        if ($isPayrollLocked) {
            // If the payroll is locked, update the payslip status to 'Published'
            Payrol::where('month', $month)->where('year', $year)
            ->where('unit_bisnis', $companyData)
            ->update(['payslip_status' => 'Published']);
            return redirect()->back()->with('success', 'Payslip published successfully');
        } else {
            // If the payroll is not locked, display an error message
            return redirect()->back()->with('error', 'Cannot publish payslip. Payroll is not locked.');
        }
    }

    public function lockPayrollNS($periode)
    {
        $employeeCode = auth()->user()->employee_code;
        $employee = Employee::where('nik', $employeeCode)->first();
        $unit_bisnis = $employee->unit_bisnis;
        if ($unit_bisnis == 'Kas') {
            Payroll::where('periode', $periode)->update(['payrol_status' => 'Locked']);
        }else{
            Payrollns::where('periode', $periode)->update(['payrol_status' => 'Locked']);
        }
        return redirect()->back()->with('success', 'Payroll locked successfully');
    }

    public function publishPayslipNS($periode)
    {
        // Check if the payroll is locked
        $employeeCode = auth()->user()->employee_code;
        $employee = Employee::where('nik', $employeeCode)->first();
        $unit_bisnis = $employee->unit_bisnis;
        if ($unit_bisnis == 'Kas') {
            $isPayrollLocked = Payroll::where('periode', $periode)->where('payrol_status', 'Locked')->exists();

            if ($isPayrollLocked) {
                // If the payroll is locked, update the payslip status to 'Published'
                Payroll::where('periode', $periode)->update(['payslip_status' => 'Published']);
                return redirect()->back()->with('success', 'Payslip published successfully');
            } else {
                // If the payroll is not locked, display an error message
                return redirect()->back()->with('error', 'Cannot publish payslip. Payroll is not locked.');
            }
            
        }else {
            $isPayrollLocked = Payrollns::where('periode', $periode)->where('payrol_status', 'Locked')->exists();

            if ($isPayrollLocked) {
                // If the payroll is locked, update the payslip status to 'Published'
                Payrollns::where('periode', $periode)->update(['payslip_status' => 'Published']);
                return redirect()->back()->with('success', 'Payslip published successfully');
            } else {
                // If the payroll is not locked, display an error message
                return redirect()->back()->with('error', 'Cannot publish payslip. Payroll is not locked.');
            }
        }
    }

    // Unlock
    public function unlockPayroll($month, $year)
    {
        $code = Auth::user()->employee_code;
        $employee = Employee::where('nik', $code)->first();
        $companyData = $employee->unit_bisnis;

        // Logic to update the status to 'Locked' for the specified month and year
        Payrol::where('month', $month)->where('year', $year)
        ->where('unit_bisnis',$companyData)
        ->update(['payrol_status' => 'Unlocked']);

        return redirect()->back()->with('success', 'Payroll unlocked successfully');
    }

    public function unpublishPayslip($month, $year)
    {
        // Check User Login
        $code = Auth::user()->employee_code;
        $employee = Employee::where('nik', $code)->first();
        $companyData = $employee->unit_bisnis;
        // Check if the payroll is locked
        $isPayrollLocked = Payrol::where('month', $month)->where('year', $year)->where('payrol_status', 'Unlocked')->exists();

        if ($isPayrollLocked) {
            // If the payroll is locked, update the payslip status to 'Published'
            Payrol::where('month', $month)->where('year', $year)
            ->where('unit_bisnis',$companyData)
            ->update(['payslip_status' => 'Unpublish']);
            return redirect()->back()->with('success', 'Payslip published successfully');
        } else {
            // If the payroll is not locked, display an error message
            return redirect()->back()->with('error', 'Cannot unpublish payslip. Payroll is locked.');
        }
    }

    public function unlockPayrollns($periode)
    {
        $employeeCode = auth()->user()->employee_code;
        $employee = Employee::where('nik', $employeeCode)->first();
        $unit_bisnis = $employee->unit_bisnis;
        if ($unit_bisnis == 'Kas') {
            Payroll::where('periode', $periode)->update(['payrol_status' => 'Unlocked']);
        }else{
            Payrollns::where('periode', $periode)->update(['payrol_status' => 'Unlocked']);
        }
        return redirect()->back()->with('success', 'Payroll locked successfully');
    }

    public function unpublishPayslipns($periode)
    {
        // Check if the payroll is locked
        $employeeCode = auth()->user()->employee_code;
        $employee = Employee::where('nik', $employeeCode)->first();
        $unit_bisnis = $employee->unit_bisnis;
        if ($unit_bisnis == 'Kas') {
            $isPayrollLocked = Payroll::where('periode', $periode)->where('payrol_status', 'Unlocked')->exists();

            if ($isPayrollLocked) {
                // If the payroll is locked, update the payslip status to 'Published'
                Payroll::where('periode', $periode)->update(['payslip_status' => 'Unpublish']);
                return redirect()->back()->with('success', 'Payslip published successfully');
            } else {
                // If the payroll is not locked, display an error message
                return redirect()->back()->with('error', 'Cannot publish payslip. Payroll is not locked.');
            }
            
        }else {
            $isPayrollLocked = Payrollns::where('periode', $periode)->where('payrol_status', 'Unlocked')->exists();

            if ($isPayrollLocked) {
                // If the payroll is locked, update the payslip status to 'Published'
                Payrollns::where('periode', $periode)->update(['payslip_status' => 'Unpublish']);
                return redirect()->back()->with('success', 'Payslip published successfully');
            } else {
                // If the payroll is not locked, display an error message
                return redirect()->back()->with('error', 'Cannot publish payslip. Payroll is not locked.');
            }
        }
    }

    public function payslipuser()
    {
        $employeeCode = auth()->user()->employee_code;
        $employee = Employee::where('nik', $employeeCode)->first();
        $unit_bisnis = $employee->unit_bisnis;
        // Ambil semua payslip berdasarkan employee_code
        $dataKaryawan = Employee::where('nik', $employeeCode)->first();
        $karyawan = json_decode($dataKaryawan, true);
        $dbSecondary = DB::connection('mysql_secondary');
        if($karyawan['organisasi'] === 'Management Leaders') {
            $payslips = Payrol::where('employee_code', $employeeCode)
                ->where('payslip_status', 'Published')
                ->get();
        }else{
            if ($unit_bisnis == 'Kas') {
                $payslips = $dbSecondary->table('payrolls')
                            ->where('payslip_status', 'Published')
                            ->get();
            }else{
                $payslips = Payrollns::where('employee_code', $employeeCode)
                        ->where('payslip_status', 'Published')
                        ->get();
            }
            
        }

        return view('pages.hc.payrol.payslip-user', compact('payslips'));
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $code = Auth::user()->employee_code;
        $DataCode = Employee::where('nik', $code)->first();

        $payslip = Payrol::findOrFail($id);

        $allowence = Component::where('type','Allowences')
                        ->where('id',$id)
                        ->where('is_active','aktif')
                        ->get();

        $deduction = Component::where('type','Deductions')
                        ->where('id',$id)
                        ->where('is_active','aktif')
                        ->get();

        $data = Payrol::where('id', $id)->get();
        return view('pages.hc.payrol.payslip-file', compact('data'));
    }

    public function showns($id)
    {
        $payslip = Payrollns::findOrFail($id);

        $dataPayslip = Payrollns::where('id', $id)->get();

        $totalallowence = $dataPayslip[0]['total_daily'] + $dataPayslip[0]['total_lembur'] + $dataPayslip[0]['uang_makan'] + $dataPayslip[0]['uang_kerajinan'];
        $totalDeductions = $dataPayslip[0]['potongan_hutang'] + $dataPayslip[0]['potongan_mess'] + $dataPayslip[0]['potongan_lain'];
    
        return view('pages.hc.payrol.ns.payslip', compact('dataPayslip','totalallowence','totalDeductions'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // auth cek
        $code = Auth::user()->employee_code;
        $DataCode = Employee::where('nik', $code)->first();

        $data = Payrol::findOrFail($id);

        $allowence = Component::where('type','Allowences')
                        ->where('id',$id)
                        ->where('is_active','aktif')
                        ->get();

        $deduction = Component::where('type','Deductions')
                        ->where('id',$id)
                        ->where('is_active','aktif')
                        ->get();

        if (!$data) {
            return redirect()->back()->with('error', 'Payrol Component not found.');
        }
        return view('pages.hc.payrol.editpayrol.edit', compact('data'));
    }

    public function editNS($id)
    {
        $employeeCode = auth()->user()->employee_code;
        $employee = Employee::where('nik', $employeeCode)->first();
        $unit_bisnis = $employee->unit_bisnis;
        if ($unit_bisnis == 'Kas') {
            $payrolComponent = Payroll::findOrFail($id);
            if (!$payrolComponent) {
                return redirect()->back()->with('error', 'Payrol Component not found.');
            }
            return view('pages.hc.payrol.editpayrol.editpayrolnscg', compact('payrolComponent'));
        }else {
            $payrolComponent = Payrollns::findOrFail($id);
            if (!$payrolComponent) {
                return redirect()->back()->with('error', 'Payrol Component not found.');
            }
            return view('pages.hc.payrol.editpayrol.editns', compact('payrolComponent'));
        }
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
        $code = Auth::user()->employee_code;
        $employee = Employee::where('nik', $code)->first();
        $unit_bisnis = $employee->unit_bisnis;
        $bulan = $request->input('month');
        $tahun = $request->input('year');

        try {
            // Begin a database transaction
            DB::beginTransaction();

            // Retrieve the payroll component by ID
            $payrolComponent = Payrol::findOrFail($id);

            // Update the payroll component fields
            $payrolComponent->basic_salary = $request->input('basic_salary');

            // Update allowances
            $allowancesData = $request->input('allowances');
            $total_allowance = 0;

            // Loop through each key-value pair in $allowancesData
            foreach ($allowancesData as $key => $values) {
                // Jumlahkan nilai-nilai dalam array untuk setiap jenis tunjangan
                $total_allowance += array_sum($values);
            }

            // Buat array data tunjangan yang mencakup nilai dan total tunjangan
            $allowance_data = [
                'data' => $allowancesData,
                'total_allowance' => $total_allowance,
            ];
            // Update deductions
            $deductionsData = $request->input('deductions');
            $total_deduction = 0;

            // Loop through each key-value pair in $allowancesData
            foreach ($deductionsData as $key => $values) {
                // Jumlahkan nilai-nilai dalam array untuk setiap jenis tunjangan
                $total_deduction += array_sum($values);
            }
            $deduction_data = [
                'data' => $deductionsData,
                'total_deduction' => $total_deduction,
            ];

            $netSalary = $payrolComponent->basic_salary + $total_allowance - $total_deduction;

            // Update the Payroll Component
            $payrolComponent->allowances = json_encode($allowance_data);
            $payrolComponent->deductions = json_encode($deduction_data);
            $payrolComponent->net_salary = $netSalary;

            // Save the updated payroll component
            $payrolComponent->save();

            // Commit the database transaction
            DB::commit();

            // Redirect back with a success message
            return redirect()->route('payslip.showByMonth', ['month' => $bulan, 'year' => $tahun])->with('success', 'Payroll component updated successfully');
        } catch (\Exception $exception) {
            // Rollback the database transaction in case of any exception
            DB::rollback();

            // Log the error
            \Log::error('Error updating payroll component: ' . $exception->getMessage());

            // Handle exceptions
            return redirect()->back()->with('error', 'Error updating payroll component. Please try again.'. $exception->getMessage());
        }
    }

    public function updateNS(Request $request, $id)
    {
        try {
            // Validate the request data
            $request->validate([
                'daily_salary' => 'required|numeric',
            ]);
    
            // Retrieve the payroll component by ID
            $payrolComponent = Payrollns::findOrFail($id);
    
            // Update the payroll component fields
            $payrolComponent->daily_salary = $request->input('daily_salary');
            $payrolComponent->total_absen = $request->input('total_absen');
            $payrolComponent->lembur_salary = $request->input('lembur_salary');
            $payrolComponent->jam_lembur = $request->input('jam_lembur');
            $payrolComponent->total_lembur = $request->input('total_lembur');
            $payrolComponent->uang_makan = $request->input('uang_makan');
            $payrolComponent->uang_kerajinan = $request->input('uang_kerajinan');
            $payrolComponent->potongan_hutang = $request->input('potongan_hutang');
            $payrolComponent->potongan_mess = $request->input('potongan_mess');
            $payrolComponent->potongan_lain = $request->input('potongan_lain');
            $payrolComponent->total_daily = $request->input('total_daily');
            $payrolComponent->thp = $request->input('thp');
            // Save the updated payroll component
            $payrolComponent->save();
    
            $periode = $request->input('periode');
    
            // Redirect back with a success message
            return redirect()->route('payslip.showbyperiode', ['periode' => $periode])->with('success', 'Payroll component updated successfully');
        } catch (QueryException $exception) {
            // Handle database-related exceptions
            return redirect()->back()->with('error', 'Error updating payroll component: ' . $exception->getMessage());
        } catch (\Exception $exception) {
            // Handle other exceptions
            return redirect()->back()->with('error', 'Error updating payroll component: ' . $exception->getMessage());
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
}
