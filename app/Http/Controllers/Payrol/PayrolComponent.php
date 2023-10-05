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
