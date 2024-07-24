<?php

namespace App\Http\Controllers\Koperasi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Koperasi\Koperasi;
use App\Koperasi\Anggota;
use App\Koperasi\SettingLoan;
use App\Employee;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoanSettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
            // Validasi input
            $request->validate([
                'min_saving' => 'required|numeric',
                'max_saving' => 'required|numeric',
                'max_limit' => 'required|numeric',
            ]);

            // Cek User Login 
            $code = Auth::user()->employee_code;
            $company = Employee::where('nik', $code)->first();
            // Simpan data ke database
            SettingLoan::create([
                'company' => $company->unit_bisnis,
                'min_saving' => $request->min_saving,
                'max_saving' => $request->max_saving,
                'max_limit' => $request->max_limit,
            ]);

            // Redirect dengan pesan sukses
            return redirect()->route('koperasi.index')->with('success', 'Settings added successfully.');
        } catch (\Exception $e) {
            // Tangani kesalahan
            return redirect()->back()->with('error', 'Failed to add settings. ' . $e->getMessage());
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
            // Validate input
            $request->validate([
                'min_saving' => 'required|numeric',
                'max_saving' => 'required|numeric',
                'max_limit' => 'required|numeric',
            ]);

            // Check User Login 
            $code = Auth::user()->employee_code;
            $company = Employee::where('nik', $code)->first();

            // Find the existing setting by ID
            $setting = SettingLoan::where('id', $id)->firstOrFail();

            // Update the existing setting record
            $setting->update([
                'company' => $company->unit_bisnis,
                'min_saving' => $request->min_saving,
                'max_saving' => $request->max_saving,
                'max_limit' => $request->max_limit,
            ]);

            // Redirect with success message
            return redirect()->route('koperasi.index')->with('success', 'Settings updated successfully.');
        } catch (\Exception $e) {
            // Handle error
            return redirect()->back()->with('error', 'Failed to update settings. ' . $e->getMessage());
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
            // Find the existing setting by ID
            $setting = SettingLoan::where('id', $id)->firstOrFail();
            
            // Delete the setting record
            $setting->delete();

            // Redirect with success message
            return redirect()->route('koperasi.index')->with('success', 'Settings deleted successfully.');
        } catch (\Exception $e) {
            // Handle error
            return redirect()->back()->with('error', 'Failed to delete settings. ' . $e->getMessage());
        }
    }
}
