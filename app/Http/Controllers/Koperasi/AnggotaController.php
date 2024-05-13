<?php

namespace App\Http\Controllers\Koperasi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Koperasi\Koperasi;
use App\Koperasi\Anggota;
use App\Employee;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AnggotaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Cek User Login 
        $code = Auth::user()->employee_code;
        $company = Employee::where('nik', $code)->first();

        $employee = Employee::where('nik', $code)->with('payrolinfo')->first();
        $koperasi = Koperasi::where('company', $company->unit_bisnis)->first();

        // Cek Member 
        $datasaya = Anggota::where('employee_code',$company->nik)->first();
        return view ('pages.koperasi.index', compact('datasaya','employee','koperasi'));
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
            // Validasi data input jika diperlukan
            $request->validate([
                'nama' => 'required|string',
                'employee_code' => 'required|numeric',
                'company' => 'required|string',
            ]);

            // Buat instance model KoperasiPage
            $anggotaDaftar = new Anggota();
            
            // Isi model dengan data dari formulir
            $anggotaDaftar->nama = $request->nama;
            $anggotaDaftar->employee_code = $request->employee_code;
            $anggotaDaftar->company = $request->company;

            $anggotaDaftar->join_date = '-';
            $anggotaDaftar->member_status = 'review';
            $anggotaDaftar->loan_limit = 0;
            $anggotaDaftar->loan_status = 'noloan';
            $anggotaDaftar->saldo_simpanan = 0;
            // Simpan data ke database
            $anggotaDaftar->save();

            // Redirect ke halaman yang sesuai setelah penyimpanan berhasil
            return redirect()->route('koperasi-page.index')->with('success', 'Thank you for registering, we are reviewing your data first.');
        } catch (\Exception $e) {
            // Tangani pengecualian di sini
            return back()->withError('Gagal menyimpan data: ' . $e->getMessage())->withInput();
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
