<?php

namespace App\Http\Controllers\Payrol;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Employee;
use App\Payrol\Component;
use App\Payrol;
use App\PayrolCM;
use App\PayrolComponent_NS;
use Illuminate\Support\Facades\Auth;

class PayrolComponent extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $code = Auth::user()->employee_code;
        $DataCode = Employee::where('nik', $code)->first();

        if ($DataCode) {
            $unit_bisnis = $DataCode->unit_bisnis;
            
            $employee = Employee::where('unit_bisnis',$unit_bisnis)->get();
            // Mengambil data Payroll berdasarkan unit bisnis dari tabel Employee
            $payrol = PayrolCM::join('karyawan', 'payrol_components.employee_code', '=', 'karyawan.nik')
                ->select('payrol_components.id', 'payrol_components.*')
                ->where('karyawan.unit_bisnis', $unit_bisnis)
                ->get();

            $parolns = PayrolComponent_NS::join('karyawan', 'payrol_component_ns.employee_code', '=', 'karyawan.nik')
            ->select('payrol_component_ns.id', 'payrol_component_ns.*')
            ->where('karyawan.unit_bisnis', $unit_bisnis)
            ->get();

        } else {
            $payrol = [];
            $parolns = [];
            $employee = [];
        }
        
        return view('pages.hc.payrol.index', compact('employee','payrol','parolns'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $code = Auth::user()->employee_code;
        $DataCode = Employee::where('nik', $code)->first();

        // Get data components
        $allowence = Component::where('type','Allowences')
                        ->where('company',$DataCode->unit_bisnis)
                        ->get();

        $deduction = Component::where('type','Deductions')
                        ->where('company',$DataCode->unit_bisnis)
                        ->get();

        $employee = Employee::where('organisasi', 'Management Leaders')
                        ->where('unit_bisnis', $DataCode->unit_bisnis)
                        ->where('resign_status', 0)
                        ->whereNotIn('nik', function($query) {
                            $query->select('employee_code')->from('payrol_components');
                        })
                        ->get();
    
        return view('pages.hc.payrol.create',compact('employee','allowence','deduction'));
    }

    public function createns()
    {
        $code = Auth::user()->employee_code;
        $DataCode = Employee::where('nik', $code)->first();
        
        $employee = Employee::where('organisasi', 'Frontline Officer')
        ->where('unit_bisnis', $DataCode->unit_bisnis)
        ->get();
        return view('pages.hc.payrol.ns.createcomponent',compact('employee'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'employee_code' => 'required',
            'basic_salary' => 'required|numeric',
            'allowances' => 'required|array',
            'deductions' => 'required|array',
        ]);
    
        try {

            $totalAllowances = array_sum($request->allowances);
            $totalDeductions = array_sum($request->deductions);

            $total_allowance = 0;
            foreach ($request->allowances as $allowanceData) {
                foreach ($allowanceData as $allowance) {
                    $total_allowance += (int) $allowance;
                }
            }

            $total_deduction = 0;
            foreach ($request->deductions as $deductionData) {
                foreach ($deductionData as $deduction) {
                    $total_deduction += (int) $deduction;
                }
            }

            $allowance_data = [
                'data' => $request->allowances,
                'total_allowance' => $total_allowance,
            ];
        
            $deduction_data = [
                'data' => $request->deductions,
                'total_deduction' => $total_deduction,
            ];

            // Calculate the take-home pay (thp)
            $thp = $request->basic_salary + $total_allowance - $total_deduction;

            // Create a new Payroll Component
            $payrolComponent = new PayrolCM();
            $payrolComponent->employee_code = $request->employee_code;
            $payrolComponent->basic_salary = $request->basic_salary;
            $payrolComponent->allowances = json_encode($allowance_data);
            $payrolComponent->deductions = json_encode($deduction_data);
            $payrolComponent->thp = $thp;
            $payrolComponent->net_salary = $thp;
            $payrolComponent->save();
    
            // Redirect with success message
            return redirect()->route('payrol-component.index')->with(['success' => 'Data Berhasil Disimpan!']);
        } catch (\Exception $e) {
            // Log the error message
            \Log::error('Error saving payroll component: ' . $e->getMessage());
    
            // Redirect back with error message
            return redirect()->back()->with(['error' => 'Terjadi kesalahan saat menyimpan data. Silakan coba lagi.']);
        }
    }

    public function storens(Request $request)
    {
        $request->validate([
            'employee_code' => 'required',
            'daily_salary' => 'required|numeric',
            'allowances' => 'required|array',
            'deductions' => 'required|array',
        ]);

        $payrolComponent = new PayrolComponent_NS();
        $payrolComponent->employee_code = $request->employee_code;
        $payrolComponent->daily_salary = $request->daily_salary;
        $payrolComponent->allowances = json_encode($request->allowances);
        $payrolComponent->deductions = json_encode($request->deductions);
        $payrolComponent->save();

        return redirect()->route('payrol-component.index')->with(['success' => 'Data Berhasil Disimpan!']);
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // auth cek
        $code = Auth::user()->employee_code;
        $DataCode = Employee::where('nik', $code)->first();

        $data = PayrolCM::find($id);

        // Get data components
        $allowence = Component::where('type','Allowences')
                        ->where('id',$id)
                        ->get();

        $deduction = Component::where('type','Deductions')
                        ->where('id',$id)
                        ->get();
        if (!$data) {
            return redirect()->route('payrol-component.index')->with('error', 'Payrol Component not found.');
        }

        return view('pages.hc.payrol.show', compact('data'));
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

        $data = PayrolCM::find($id);

        // Get data components
        $allowence = Component::where('type','Allowences')
                        ->where('id',$id)
                        ->get();

        $deduction = Component::where('type','Deductions')
                        ->where('id',$id)
                        ->get();
        if (!$data) {
            return redirect()->route('payrol-component.index')->with('error', 'Payrol Component not found.');
        }

        return view('pages.hc.payrol.editcomponent.edit', compact('data'));
    }


    public function editns($id)
    {
        $payrolComponent = PayrolComponent_NS::find($id);

        if (!$payrolComponent) {
            return redirect()->route('payrol-component.index')->with('error', 'Payrol Component not found.');
        }

        return view('pages.hc.payrol.editcomponent.editns', compact('payrolComponent'));
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
            // Validate the incoming request data
            $validatedData = $request->validate([
                'employee_code' => 'required',
                'basic_salary' => 'required|numeric',
                'allowances' => 'required|array',
                'deductions' => 'required|array',
            ]);

            // Find the PayrollComponent by ID
            $payrollComponent = PayrolCM::find($id);

            if (!$payrollComponent) {
                return redirect()->back()->with('error', 'PayrollComponent not found.');
            }

            // Calculate total allowances and deductions
            $total_allowance = 0;
            foreach ($validatedData['allowances'] as $allowanceData) {
                foreach ($allowanceData as $allowance) {
                    $total_allowance += (int) $allowance;
                }
            }

            $total_deduction = 0;
            foreach ($validatedData['deductions'] as $deductionData) {
                foreach ($deductionData as $deduction) {
                    $total_deduction += (int) $deduction;
                }
            }

            $allowance_data = [
                'data' => $validatedData['allowances'],
                'total_allowance' => $total_allowance,
            ];

            $deduction_data = [
                'data' => $validatedData['deductions'],
                'total_deduction' => $total_deduction,
            ];

            // Calculate the take-home pay (thp)
            $thp = $validatedData['basic_salary'] + $total_allowance - $total_deduction;

            // Update the PayrollComponent with the validated data
            $payrollComponent->employee_code = $validatedData['employee_code'];
            $payrollComponent->basic_salary = $validatedData['basic_salary'];
            $payrollComponent->allowances = json_encode($allowance_data);
            $payrollComponent->deductions = json_encode($deduction_data);
            $payrollComponent->thp = $thp;
            $payrollComponent->net_salary = $thp;
            $payrollComponent->save();

            return redirect()->route('payrol-component.index')->with('success', 'PayrollComponent updated successfully.');
        } catch (\Exception $e) {
            // Log the error message
            \Log::error('Error updating payroll component: ' . $e->getMessage());

            // Redirect back with error message
            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }


    public function updateNS(Request $request, $id)
    {
        $validatedData = $request->validate([
            'daily_salary' => 'required|numeric',
            'allowances.lembur.0' => 'required|numeric',
            'allowances.uang_makan.0' => 'required|numeric',
            'allowances.kerajinan.0' => 'required|numeric',
            'deductions.mess.0' => 'required|numeric',
            'deductions.hutang.0' => 'required|numeric',
            'deductions.lain_lain.0' => 'required|numeric',
        ]);

        // Find the PayrollComponent by ID
        $payrollComponent = PayrolComponent_NS::find($id);

        if (!$payrollComponent) {
            return redirect()->back()->with('error', 'PayrollComponent not found.');
        }

        // Update the PayrollComponent with the validated data
        $payrollComponent->daily_salary = $validatedData['daily_salary'];
        $payrollComponent->allowances = json_encode([
            'lembur' => [$validatedData['allowances']['lembur'][0]],
            'uang_makan' => [$validatedData['allowances']['uang_makan'][0]],
            'kerajinan' => [$validatedData['allowances']['kerajinan'][0]],
        ]);
        $payrollComponent->deductions = json_encode([
            'mess' => [$validatedData['deductions']['mess'][0]],
            'hutang' => [$validatedData['deductions']['hutang'][0]],
            'lain_lain' => [$validatedData['deductions']['lain_lain'][0]],
        ]);
        
        $payrollComponent->save();

        return redirect()->route('payrol-component.index')->with('success', 'PayrollComponent updated successfully.');
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
