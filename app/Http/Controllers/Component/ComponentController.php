<?php

namespace App\Http\Controllers\Component;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Component\AdditionalComponents;
use App\Component\ComponentDetails;
use App\Component\ComponentMaster;

use App\Employee;
use App\Payrol\Component;

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

        $component = ComponentMaster::where('company', $company->unit_bisnis)->get();
        return view('pages.app-setting.component.index', compact('component'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $code = Auth::user()->employee_code;
        $company = Employee::where('nik', $code)->first();
        // Ambil daftar karyawan berdasarkan unit bisnis
        $employees = Employee::where('unit_bisnis', $company->unit_bisnis)
                        ->orderBy('nama', 'asc')
                        ->get();

        $component = Component::where('company', $company->unit_bisnis)
                     ->get();

        return view('pages.app-setting.component.create', compact('employees','component'));
    }

    public function getEmployeesComponent(Request $request) {
        $code = Auth::user()->employee_code;
        $company = Employee::where('nik', $code)->first();
        // Ambil daftar karyawan berdasarkan unit bisnis
        $employees = Employee::where('unit_bisnis', $company->unit_bisnis)
                     ->get();

        return response()->json(['employees' => $employees]);
    }

    public function getComponent() {
        $code = Auth::user()->employee_code;
        $company = Employee::where('nik', $code)->first();
        // Ambil daftar karyawan berdasarkan unit bisnis
        $component = Component::where('company', $company->unit_bisnis)
                     ->get();

        return response()->json(['component' => $component]);
    }


    function generateRandomCode($length = 6) {
        $characters = '0123456789';
        $code = '';

        for ($i = 0; $i < $length; $i++) {
            $code .= $characters[rand(0, strlen($characters) - 1)];
        }

        return $code;
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
                'title' => 'required',
                'type' => 'required',
                'effective_date' => 'required|date',
                'employee_code' => 'array',
                'component_code' => 'array',
                'nominal' => 'array',
            ]);
        
            $randomCode = $this->generateRandomCode();
        
            // Simpan data ke tabel additional_component_masters
            $componentMaster = new ComponentMaster([
                'title' => $request->input('title'),
                'code' => $randomCode,
                'type' => $request->input('type'),
                'effective_date' => $request->input('effective_date'),
                'company' => $company->unit_bisnis,
            ]);
        
            $componentMaster->save();
        
            // Looping untuk menyimpan data ke tabel additional_component_details
            for ($i = 0; $i < count($request->employee_code); $i++) {
                $employee = Employee::where('nik', $request->employee_code[$i])->first();
        
                $componentData = Component::find($request->component_code[$i]);
        
                $componentDetails = new ComponentDetails([
                    'employee_code' => $employee->nik,
                    'employee_name' => $employee->nama,
                    'code_master' => $randomCode,
                    'component_code' => $request->component_code[$i],
                    'component_name' => $componentData->name,
                    'nominal' => $request->nominal[$i],
                ]);
        
                $componentDetails->save();
            }
        
            return redirect()->route('additional-component.index')->with('success', 'Data successfully saved.');
        } catch (\Exception $e) {
            dd($e); // Tambahkan pernyataan dd di sini untuk melihat nilai dari exception
            // Tangani kesalahan di sini, misalnya log kesalahan atau kirim pesan ke pengguna
            return redirect()->back()->with('error', 'Failed to save data. ' . $e->getMessage());
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

    public function showDetails($code_master)
    {
        try {
            // Temukan data ComponentDetails berdasarkan code_master
            $componentDetails = ComponentDetails::where('code_master', $code_master)->get();
            $componentMaster = ComponentMaster::where('code', $code_master)->first();

            if ($componentDetails->isEmpty()) {
                // Handle jika data tidak ditemukan
                return redirect()->route('additional-component.index')->with('error', 'Data not found.');
            }

            return view('pages.app-setting.component.show', compact('componentDetails','componentMaster'));
        } catch (\Exception $e) {
            dd($e); // Gunakan dd untuk debugging
            // Tangani kesalahan di sini, misalnya log kesalahan atau kirim pesan ke pengguna
            return redirect()->route('additional-component.index')->with('error', 'Failed to fetch data. ' . $e->getMessage());
        }
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
