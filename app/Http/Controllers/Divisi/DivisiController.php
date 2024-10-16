<?php

namespace App\Http\Controllers\Divisi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Divisi\Divisi;
use App\Employee;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DivisiController extends Controller
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

        $karyawan = Employee::where('unit_bisnis',$company->unit_bisnis)
                            ->where('resign_status',0)
                            ->where('organisasi','Management Leaders')->get();

        $divisi = Divisi::where('company', $company->unit_bisnis)->get();
        return view('pages.company.divisi.index', compact('divisi','karyawan'));
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
        $code = Auth::user()->employee_code;
        $company = Employee::where('nik', $code)->first();

        $request->validate([
            'name' => 'required',
            'manager' => 'required'
        ]);
        
        $divisi = new Divisi();
        $divisi->name = $request->input('name');
        $divisi->manager = $request->input('manager');
        $divisi->company = $company->unit_bisnis;
        $divisi->save();

        return redirect()->route('divisi.index')->with('success', 'Divisi Successfully Added');
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
            // Validate the form data
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'manager' => 'required|string|max:255',
            ]);
    
            // Update the data
            $divisiData = Divisi::findOrFail($id);
            $divisiData->update($validatedData);
    
            // Redirect back with a success message
            return redirect()->route('divisi.index')->with('success', 'Data updated successfully');
        } catch (\Exception $e) {
            // Handle exceptions, you can log or return an error message
            return redirect()->back()->with('error', 'Error updating data: ' . $e->getMessage())->withErrors($e->getMessage());
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
            $divisi = Divisi::findOrFail($id);
            // If divisi exists, delete it
            if ($divisi) {
                $divisi->delete();
            }

            return redirect()->route('divisi.index')->with('success', 'Divisi Successfully Deleted');
        } catch (ModelNotFoundException $e) {
            return redirect()->route('divisi.index')->with('error', 'Divisi not found');
        } catch (\Exception $e) {
            // Handle other exceptions
            return redirect()->route('divisi.index')->with('error', 'An error occurred while deleting the Jabatan');
        }
    }
}
