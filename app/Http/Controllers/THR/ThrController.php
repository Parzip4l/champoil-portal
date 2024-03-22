<?php

namespace App\Http\Controllers\THR;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Route;

// Model
use App\Employee;
use App\THR\ThrModel;
use App\THR\ThrComponent;
use App\THR\ThrComponentModel;

class ThrController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $code = Auth::user()->employee_code;
        $employees = Employee::where('nik', $code)->first();
        $company = $employees->unit_bisnis;

        $datakaryawan = Employee::where('unit_bisnis',$company)
                        ->where('resign_status',0)                        
                        ->get();

        $data = ThrModel::all();

        return view('pages.hc.thr.index', compact('data','datakaryawan'));
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
        $unit_bisnis = $employee->unit_bisnis;
        $employeeCodes = $request->input('employee_code');
        $tahun = $request->input('year');

        foreach ($employeeCodes as $code) {
            $thrComponent = ThrComponent::where('employee_code', $code)->first();
        
            if ($thrComponent) {
                // Simpan data payroll
                $payroll = new ThrModel();
                $payroll->employee_code = $code;
                $payroll->gaji_pokok = $thrComponent->gaji_pokok;
                $payroll->tahun = $tahun;
                $payroll->allowances = $thrComponent->allowances;
                $payroll->deductions = $thrComponent->deductions;
                $payroll->thp = $thrComponent->thp;
                $payroll->thr_status = 'Unlocked';
                $payroll->slip_status = 'Unpublish';
                $payroll->company = $unit_bisnis;
                $payroll->run_by = $employee->nama;
                $payroll->save();
            }
        }
        

        return redirect()->route('thr.index')->with('success', 'THR successfully created');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $thrslip = ThrModel::findOrFail($id);

        $dataslip = ThrModel::where('id', $id)->get();
        return view('pages.hc.thr.view', compact('dataslip'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            // Mengambil data yang akan diedit berdasarkan ID
            $data = ThrModel::findOrFail($id);
            $allowence = ThrComponentModel::where('type','Allowance')
                    ->where('id',$id)
                    ->get();
            $deduction = ThrComponentModel::where('type','Deduction')
                        ->where('id',$id)
                        ->get();
            return view('pages.hc.thr.edit', compact('data','allowence','deduction'));
        } catch (\Exception $e) {
            return redirect()->route('index_route_name')->with('error', 'Gagal mengambil data untuk diedit. Error: ' . $e->getMessage());
        }
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
                'employee_code' => 'required|string',
                'gaji_pokok' => 'required',
                'allowance.*' => 'required',
                'deduction.*' => 'required',
            ]);
        
            $thrComponent = ThrModel::findOrFail($id); // $id adalah ID data yang akan diupdate
        
            $thrComponent->employee_code = $validatedData['employee_code'];
            $thrComponent->gaji_pokok = $validatedData['gaji_pokok'];
        
            // Hitung total allowance
            $total_allowance = 0;
            foreach ($request->allowance as $allowanceData) {
                foreach ($allowanceData as $allowance) {
                    $total_allowance += (int) $allowance;
                }
            }
        
            // Hitung total deduction
            $total_deduction = 0;
            foreach ($request->deduction as $deductionData) {
                foreach ($deductionData as $deduction) {
                    $total_deduction += (int) $deduction;
                }
            }
        
            // Hitung total thp
            $thp = $validatedData['gaji_pokok'] + $total_allowance - $total_deduction;
        
            // Update data allowances dan deductions
            $allowance_data = [
                'data' => $request->allowance,
                'total_allowance' => $total_allowance,
            ];
        
            $deduction_data = [
                'data' => $request->deduction,
                'total_deduction' => $total_deduction,
            ];
        
            $thrComponent->allowances = json_encode($allowance_data);
            $thrComponent->deductions = json_encode($deduction_data);
            $thrComponent->thp = $thp;
        
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
            $thrComponent = ThrModel::findOrFail($id); // Cari data berdasarkan ID
    
            $thrComponent->delete(); // Hapus data
    
            return redirect()->back()->with('success', 'Data berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus data. Error: ' . $e->getMessage());
        }
    }
}
