<?php

namespace App\Http\Controllers\CgControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\ModelCG\Jabatan;
use App\Employee;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class JabatanControllers extends Controller
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

        $jabatan = Jabatan::where('parent_category', $company->unit_bisnis)->get();
        return view('pages.hc.kas.jabatan.index', compact('jabatan'));
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
            'name' => 'required'
        ]);

        $uuid = Str::uuid()->toString();
        
        $jabatan = new Jabatan();
        $jabatan->id = $uuid;
        $jabatan->name = $request->input('name');
        $jabatan->parent_category = $company->unit_bisnis;
        $jabatan->save();

        return redirect()->route('jabatan.index')->with('success', 'Jabatan Successfully Added');
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
            ]);
    
            // Update the data
            $jabatanData = Jabatan::findOrFail($id);
            $jabatanData->update($validatedData);
    
            // Redirect back with a success message
            return redirect()->route('jabatan.index')->with('success', 'Data updated successfully');
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
            $jabatan = Jabatan::findOrFail($id);
            // If Jabatan exists, delete it
            if ($jabatan) {
                $jabatan->delete();
            }

            return redirect()->route('jabatan.index')->with('success', 'Jabatan Successfully Deleted');
        } catch (ModelNotFoundException $e) {
            return redirect()->route('jabatan.index')->with('error', 'Jabatan not found');
        } catch (\Exception $e) {
            // Handle other exceptions
            return redirect()->route('jabatan.index')->with('error', 'An error occurred while deleting the Jabatan');
        }
    }
}
