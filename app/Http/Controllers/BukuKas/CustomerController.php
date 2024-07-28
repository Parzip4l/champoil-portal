<?php

namespace App\Http\Controllers\BukuKas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

use App\KasManagement\CustomerManagement;
use App\Employee;

class CustomerController extends Controller
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

        $customer = CustomerManagement::where('company',$employee->unit_bisnis)->get();
        return view('pages.BukuKas.customer.index', compact('customer'));
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
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'alamat' => 'nullable|string',
            'handphone' => 'required|numeric',
            'job_status' => 'required|string|in:TO DO,In Progress,On Review,Completed',
        ]);
    
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
    
        try {
            $code = Auth::user()->employee_code;
            $employee = Employee::where('nik', $code)->first();
    
            $customer = new CustomerManagement();
            $customer->name = $request->input('name');
            $customer->alamat = $request->input('alamat'); 
            $customer->handphone = $request->input('handphone'); 
            $customer->job_status = $request->input('job_status'); 
            $customer->company = $employee->unit_bisnis;
            $customer->save();
    
            return redirect()->route('customer.index')->with('success', 'Customer added successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to add customer: ' . $e->getMessage());
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
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'alamat' => 'nullable|string',
            'handphone' => 'required|numeric',
            'job_status' => 'required|string|in:TO DO,In Progress,On Review,Completed',
        ]);
    
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
    
        try {
            $customer = CustomerManagement::findOrFail($id);
            $customer->name = $request->input('name');
            $customer->alamat = $request->input('alamat');
            $customer->handphone = $request->input('handphone');
            $customer->job_status = $request->input('job_status');
            $customer->save();
    
            return redirect()->route('customer.index')->with('success', 'Customer updated successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update customer: ' . $e->getMessage());
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
        try {
            $customer = CustomerManagement::findOrFail($id);
            $customer->delete();
    
            return redirect()->route('customer.index')->with('success', 'Customer deleted successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to delete customer: ' . $e->getMessage());
        }
    }
}
