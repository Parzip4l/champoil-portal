<?php

namespace App\Http\Controllers\Organisasi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Organisasi\Organisasi;
use App\Employee;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class OrganisasiController extends Controller
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

        $organisasi = Organisasi::where('company', $company->unit_bisnis)->get();
        return view('pages.company.organisasi.index', compact('organisasi'));
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
        
        $organisasi = new Organisasi();
        $organisasi->name = $request->input('name');
        $organisasi->company = $company->unit_bisnis;
        $organisasi->save();

        return redirect()->route('organisasi.index')->with('success', 'Divisi Successfully Added');
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
            $divisiData = Organisasi::findOrFail($id);
            $divisiData->update($validatedData);
    
            // Redirect back with a success message
            return redirect()->route('organisasi.index')->with('success', 'Data updated successfully');
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
            $organisasi = Organisasi::findOrFail($id);
            // If divisi exists, delete it
            if ($organisasi) {
                $organisasi->delete();
            }

            return redirect()->route('organisasi.index')->with('success', 'organisasi Successfully Deleted');
        } catch (ModelNotFoundException $e) {
            return redirect()->route('organisasi.index')->with('error', 'organisasi not found');
        } catch (\Exception $e) {
            // Handle other exceptions
            return redirect()->route('organisasi.index')->with('error', 'An error occurred while deleting the Jabatan');
        }
    }
}
