<?php

namespace App\Http\Controllers\Payrol;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Payrol\Component;
use App\Employee;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ComponentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $code = Auth::user()->employee_code;
        $company = Employee::where('nik', $code)->first();

        $data = Component::where('company', $company->unit_bisnis)->get();
        $data2 = Component::where('company', $company->unit_bisnis)->get();
        return view('pages.hc.kas.payrol-component.index',compact('data','data2'));
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
        try {
            $code = Auth::user()->employee_code;
            $company = Employee::where('nik', $code)->first();

            $request->validate([
                'name' => 'required',
                'type' => 'required',
                'is_taxable' => 'required',
            ]);
            
            $ComponentData = new Component();
            $ComponentData->name = $request->name;
            $ComponentData->type = $request->type;
            $ComponentData->is_taxable = $request->is_taxable;
            $ComponentData->company = $company->unit_bisnis;
            $ComponentData->is_active = 'nonaktif';
            $ComponentData->save();

            return redirect()->route('component-data.index')->with('success', 'Component Successfully Added');
        } catch (\Exception $e) {
            return redirect()->route('component-data.index')->with('error', 'Failed to add component: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    public function updateStatus(Request $request, $id)
    {
        // Validasi input
        $request->validate([
            'is_active' => 'required|in:aktif,nonaktif',
        ]);

        // Temukan data berdasarkan ID
        $data = Component::find($id);

        if (!$data) {
            return response()->json(['success' => false, 'message' => 'Data not found'], 404);
        }

        // Perbarui status aktif
        $data->is_active = $request->input('is_active');
        $data->save();

        return response()->json(['success' => true, 'message' => 'Status updated successfully']);
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
        try {
            // Validate the incoming request data
            $validatedData = $request->validate([
                'name' => 'required',
                'is_taxable' => 'required',
                'type' => 'required',
            ]);

            // Find the PayrollComponent by ID
            $payrollComponent = Component::find($id);

            if (!$payrollComponent) {
                return redirect()->back()->with('error', 'PayrollComponent not found.');
            }

            // Update the PayrollComponent with the validated data
            $payrollComponent->name = $validatedData['name'];
            $payrollComponent->type = $validatedData['type'];
            $payrollComponent->is_taxable = $validatedData['is_taxable'];
            
            $payrollComponent->save();

            return redirect()->route('component-data.index')->with('success', 'Payroll Component updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
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
        $Component = Component::find($id);
        $Component->delete();
        return redirect()->route('component-data.index')->with('success', 'Component Successfully Deleted');
    }
}
