<?php

namespace App\Http\Controllers\Loan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Loan\LoanModel;
use App\Employee;

class LoanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $Loandata = LoanModel::all();
        $karyawan = Employee::where('unit_bisnis','Kas')->get();
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
