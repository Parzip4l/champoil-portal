<?php

namespace App\Http\Controllers\Koperasi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Koperasi\Koperasi;
use App\Koperasi\Anggota;
use App\Employee;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class KoperasiController extends Controller
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

        // Redirect View 
        $koperasi = Koperasi::where('company', $company->unit_bisnis)->get();
        $anggotaPending = Anggota::where('company', $company->unit_bisnis)
                        ->whereNotIn('member_status', ['active'])
                        ->get();

        $anggota = Anggota::where('company', $company->unit_bisnis)
                        ->where('member_status', 'active')
                        ->get();

        return view('pages.app-setting.koperasi.index', compact('koperasi','anggotaPending','anggota'));
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
                'potongan' => 'required|numeric',
                'iuran' => 'required|numeric',
                'persayaratan' => 'required|string',
            ]);

            // Cek User Login 
            $code = Auth::user()->employee_code;
            $company = Employee::where('nik', $code)->first();
            // Simpan data ke database
            Koperasi::create([
                'company' => $company->unit_bisnis,
                'potongan' => $request->potongan,
                'iuran' => $request->iuran,
                'persayaratan' => $request->persayaratan,
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
            // Validasi input jika diperlukan
            $request->validate([
                'potongan' => 'required|numeric',
                'iuran' => 'required|numeric',
                'persayaratan' => 'required|string',
            ]);

            // Cari data Koperasi berdasarkan ID
            $koperasi = Koperasi::findOrFail($id);

            // Update data Koperasi
            $koperasi->potongan = $request->potongan;
            $koperasi->iuran = $request->iuran;
            $koperasi->persayaratan = $request->persayaratan;
            
            // Simpan perubahan
            $koperasi->save();

            // Redirect atau return response yang sesuai
            return redirect()->route('koperasi.index')->with('success', 'Settings Koperasi berhasil diperbarui.');
        } catch (\Exception $e) {
            // Tangani error
            return back()->withInput()->withErrors(['error' => 'Gagal memperbarui pengaturan Koperasi. Silakan coba lagi.']);
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
        //
    }
}
