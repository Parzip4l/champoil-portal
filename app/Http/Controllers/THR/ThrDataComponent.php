<?php

namespace App\Http\Controllers\THR;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

// Model
use App\Employee;
use App\THR\ThrComponentModel;
use App\THR\ThrComponent;

class ThrDataComponent extends Controller
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

        if (!$employee) {
            return redirect()->back()->with('error', 'Data karyawan tidak ditemukan.');
        }

        $company = $employee->unit_bisnis;

        $data = ThrComponent::where('company', $company)->get();
        return view('pages.hc.thr.component.index', compact('data'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $code = Auth::user()->employee_code;
        $employee = Employee::where('nik', $code)->first();
        $company = $employee->unit_bisnis;

        $allowence = ThrComponentModel::where('type','Allowance')
                    ->where('company',$company)
                    ->get();
        $deduction = ThrComponentModel::where('type','Deduction')
                    ->where('company',$company)
                    ->get();
        $employee = Employee::where('unit_bisnis',$company)->get();

        return view('pages.hc.thr.component.create', compact('allowence','deduction','employee'));
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
            $employee = Employee::where('nik', $code)->first();
            $company = $employee->unit_bisnis;

            $validatedData = $request->validate([
                'employee_code' => 'required|string',
                'gaji_pokok' => 'required',
                'allowance.*' => 'required',
                'deduction.*' => 'required',
            ]);
        
            $gaji_pokok = $validatedData['gaji_pokok'];

            $total_allowance = 0;
            foreach ($request->allowance as $allowanceData) {
                foreach ($allowanceData as $allowance) {
                    $total_allowance += (int) $allowance;
                }
            }

            $total_deduction = 0;
            foreach ($request->deduction as $deductionData) {
                foreach ($deductionData as $deduction) {
                    $total_deduction += (int) $deduction;
                }
            }
            
            $thp = $gaji_pokok + $total_allowance - $total_deduction;
            // Convert to json
            $allowance_data = [
                'data' => $request->allowance,
                'total_allowance' => $total_allowance,
            ];
        
            $deduction_data = [
                'data' => $request->deduction,
                'total_deduction' => $total_deduction,
            ];
        
            $componentDataThr = new ThrComponent();
            $componentDataThr->employee_code = $validatedData['employee_code'];
            $componentDataThr->gaji_pokok = $gaji_pokok;
            $componentDataThr->allowances = json_encode($allowance_data);
            $componentDataThr->deductions = json_encode($deduction_data);
            $componentDataThr->thp = $thp;
            $componentDataThr->company = $company;
            $componentDataThr->save();
        
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
        try {
            // Mengambil data yang akan diedit berdasarkan ID
            $data = ThrComponent::findOrFail($id);
            $allowence = ThrComponentModel::where('type','Allowance')
                    ->where('id',$id)
                    ->get();
            $deduction = ThrComponentModel::where('type','Deduction')
                        ->where('id',$id)
                        ->get();
            return view('pages.hc.thr.component.edit', compact('data','allowence','deduction'));
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
        
            $thrComponent = ThrComponent::findOrFail($id); // $id adalah ID data yang akan diupdate
        
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
            $thrComponent = ThrComponent::findOrFail($id); // Cari data berdasarkan ID
    
            $thrComponent->delete(); // Hapus data
    
            return redirect()->back()->with('success', 'Data berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus data. Error: ' . $e->getMessage());
        }
    }
}
