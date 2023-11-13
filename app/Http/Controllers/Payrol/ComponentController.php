<?php

namespace App\Http\Controllers\Payrol;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Payrol\Component;

class ComponentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Component::all();
        $data2 = Component::all();
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
            $request->validate([
                'name' => 'required',
                'type' => 'required',
                'is_taxable' => 'required',
            ]);

            $ComponentData = new Component();
            $ComponentData->name = $request->name;
            $ComponentData->type = $request->type;
            $ComponentData->is_taxable = $request->is_taxable;
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
