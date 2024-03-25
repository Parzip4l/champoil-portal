<?php

namespace App\Http\Controllers\THR;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// Model
use App\Employee;
use App\THR\ThrComponentModel;

class ThrComponentController extends Controller
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
        $company = $employee->unit_bisnis;

        $data = ThrComponentModel::where('company', $company)->get();
        $update = ThrComponentModel::where('company', $company)->get();
        
        return view('pages.app-setting.thr.index', compact('data','update'));
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
        $employee = Employee::where('nik', $code)->first();
        $company = $employee->unit_bisnis;

        try {
            $validatedData = $request->validate([
                'front_text' => 'required|string',
                'name' => 'required|string',
                'type' => 'required|in:Allowance,Deduction',
            ]);

            $thrComponent = new ThrComponentModel();
            $thrComponent->front_text = $validatedData['front_text'];
            $thrComponent->name = $validatedData['name'];
            $thrComponent->type = $validatedData['type'];
            $thrComponent->company = $company;
            $thrComponent->save();

            return redirect()->back()->with('success', 'Data berhasil disimpan.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menyimpan data. Error: ' . $e->getMessage());
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
            $validatedData = $request->validate([
                'front_text' => 'required|string',
                'name' => 'required|string',
                'type' => 'required|in:Allowance,Deduction',
            ]);

            $thrComponent = ThrComponentModel::findOrFail($id);
            $thrComponent->front_text = $validatedData['front_text'];
            $thrComponent->name = $validatedData['name'];
            $thrComponent->type = $validatedData['type'];
            $thrComponent->save();

            return redirect()->back()->with('success', 'Data berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memperbarui data. Error: ' . $e->getMessage());
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
            $thrComponent = ThrComponentModel::findOrFail($id);
            $thrComponent->delete();

            return redirect()->back()->with('success', 'Data berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus data. Error: ' . $e->getMessage());
        }
    }
}
