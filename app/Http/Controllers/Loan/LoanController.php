<?php

namespace App\Http\Controllers\Loan;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Loan\LoanModel;
use App\Employee;
use App\User;

class LoanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $userId = Auth::id();
        $EmployeeCode = Auth::user()->employee_code;
        $company = Employee::where('nik', $EmployeeCode)->first();

        if (!$company) {
            return redirect()->route('employee-loan.index')->with('error', 'Company data not found.');
        }

        $Loandata = LoanModel::join('karyawan','karyawan.nik','=','loans.employee_id')->where('unit_bisnis',$company->unit_bisnis)->get();

        $karyawan = Employee::where('unit_bisnis', $company->unit_bisnis)->get();
        
        return view('pages.hc.loan.index', compact('Loandata','karyawan'));
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
                'employee_id' => 'required',
                'amount' => 'required|numeric|min:1',
                'installments' => 'required|numeric|min:1',
            ]);
    
            // Hitung besaran cicilan per bulan
            $installmentAmount = $request->amount / $request->installments;
    
            // Buat entri pinjaman
            $loan = new LoanModel();
            $loan->employee_id = $request->employee_id;
            $loan->amount = $request->amount;
            $loan->remaining_amount = $request->amount;
            $loan->installments = $request->installments;
            $loan->installment_amount = $installmentAmount;
            $loan->save();
    
            return redirect()->route('employee-loan.index')->with('success', 'Pinjaman berhasil dibuat.');
        } catch (\Exception $e) {
            return redirect()->route('employee-loan.index')->with('error', 'Gagal: ' . $e->getMessage());
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
            // Validasi input sesuai kebutuhan
            $request->validate([
                'amount' => 'required|numeric',
                'installments' => 'required|numeric',
                'remaining_amount' => 'required|numeric',
                'is_paid' => 'required|in:0,1',
            ]);

            // Temukan data berdasarkan ID
            $data = LoanModel::find($id);

            // Periksa apakah data ditemukan
            if (!$data) {
                throw new \Exception('Data not found');
            }

            // Update data
            $data->update([
                'amount' => $request->amount,
                'installments' => $request->installments,
                'remaining_amount' => $request->remaining_amount,
                'is_paid' => $request->is_paid,
            ]);

            // Redirect dengan pesan sukses
            return redirect()->route('employee-loan.index')->with('success', 'Data berhasil diperbarui');
        } catch (\Exception $e) {
            // Redirect dengan pesan error
            return redirect()->route('employee-loan.index')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
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
            $data = LoanModel::findOrFail($id);

            // Delete the project
            $data->delete();

            return redirect()->route('employee-loan.index')->with('success', 'Loan Data Successfully Deleted');
        } catch (ModelNotFoundException $e) {
            return redirect()->route('employee-loan.index')->with('error', 'Loan not found');
        } catch (\Exception $e) {
            // Handle other exceptions
            return redirect()->route('employee-loan.index')->with('error', 'An error occurred while deleting the loan data');
        }
    }
}
