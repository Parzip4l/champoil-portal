<?php

namespace App\Http\Controllers\Payrol;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Employee;
use App\Payrol;
use App\PayrolCM;
use App\PayrolComponent_NS;

class PayrolComponent extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $employee = Employee::all();
        $payrol = PayrolCM::all();
        $parolns = PayrolComponent_NS::all();
        return view('pages.hc.payrol.index', compact('employee','payrol','parolns'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $employee = Employee::where('organisasi', 'Management Leaders')->get();
        return view('pages.hc.payrol.create',compact('employee'));
    }

    public function createns()
    {
        $employee = Employee::where('organisasi', 'Frontline Officer')->get();
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

        $payrolComponent = new PayrolCM();
        $payrolComponent->employee_code = $request->employee_code;
        $payrolComponent->basic_salary = $request->basic_salary;
        $payrolComponent->allowances = json_encode($request->allowances);
        $payrolComponent->deductions = json_encode($request->deductions);
        $payrolComponent->thp = $request->thp;
        $payrolComponent->net_salary = $request->thp;
        $payrolComponent->save();

        return redirect()->route('payrol-component.index')->with(['success' => 'Data Berhasil Disimpan!']);
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
        $payrolComponent = PayrolCM::find($id);

        if (!$payrolComponent) {
            return redirect()->route('payrol-component.index')->with('error', 'Payrol Component not found.');
        }

        return view('pages.hc.payrol.show', compact('payrolComponent'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $payrolComponent = PayrolCM::find($id);

        if (!$payrolComponent) {
            return redirect()->route('payrol-component.index')->with('error', 'Payrol Component not found.');
        }

        return view('pages.hc.payrol.editcomponent.edit', compact('payrolComponent'));
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
                'basic_salary' => 'required|numeric',
                'allowances.t_struktural.0' => 'required|numeric',
                'allowances.t_kinerja.0' => 'required|numeric',
                'allowances.t_alatkerja.0' => 'required|numeric',
                'allowances.t_allowance.0' => 'required|numeric',
                'deductions.bpjs_ks.0' => 'required|numeric',
                'deductions.bpsj_tk.0' => 'required|numeric',
                'deductions.pph21.0' => 'required|numeric',
                'deductions.p_hutang.0' => 'required|numeric',
                'deductions.t_deduction.0' => 'required|numeric',
                'thp' => 'required|numeric', 
            ]);

            // Find the PayrollComponent by ID
            $payrollComponent = PayrolCM::find($id);

            if (!$payrollComponent) {
                return redirect()->back()->with('error', 'PayrollComponent not found.');
            }

            // Update the PayrollComponent with the validated data
            $payrollComponent->basic_salary = $validatedData['basic_salary'];
            $payrollComponent->allowances = json_encode([
                't_struktural' => [$validatedData['allowances']['t_struktural'][0]],
                't_kinerja' => [$validatedData['allowances']['t_kinerja'][0]],
                't_alatkerja' => [$validatedData['allowances']['t_alatkerja'][0]],
                't_allowance' => [$validatedData['allowances']['t_allowance'][0]],
            ]);
            $payrollComponent->deductions = json_encode([
                'bpjs_ks' => [$validatedData['deductions']['bpjs_ks'][0]],
                'bpsj_tk' => [$validatedData['deductions']['bpsj_tk'][0]],
                'pph21' => [$validatedData['deductions']['pph21'][0]],
                'p_hutang' => [$validatedData['deductions']['p_hutang'][0]],
                't_deduction' => [$validatedData['deductions']['t_deduction'][0]],
            ]);
            $payrollComponent->net_salary = $validatedData['thp'];
            
            $payrollComponent->save();

            return redirect()->route('payrol-component.index')->with('success', 'PayrollComponent updated successfully.');
        } catch (\Exception $e) {
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
