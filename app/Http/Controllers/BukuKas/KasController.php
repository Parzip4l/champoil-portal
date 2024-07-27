<?php

namespace App\Http\Controllers\BukuKas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

use App\KasManagement\KasManagement;
use App\Employee;

class KasController extends Controller
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

        $dataLogin = json_decode(Auth::user()->permission);

        $datakas = KasManagement::where('company',$employee->unit_bisnis)->where('office',$employee->divisi)->get();

        return view('pages.BukuKas.index',compact('datakas'));
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

        $validatedData = $request->validate([
            'judul' => 'required|string|max:255',
            'desc' => 'nullable|string',
            'pemasukan' => 'required|numeric',
            'pengeluaran' => 'required|numeric',
        ]);

        DB::beginTransaction();
        try {
            // Retrieve the last record for the same office
            $lastRecord = KasManagement::where('office', $employee->divisi)->latest('id')->first();
            $saldoAkhir = $lastRecord ? $lastRecord->saldototal : 0;

            $saldoAkhir += $validatedData['pemasukan'];
            $saldoAkhir -= $validatedData['pengeluaran'];

            $bukuKas = new KasManagement();
            $bukuKas->judul = $validatedData['judul'];
            $bukuKas->desc = $validatedData['desc'];
            $bukuKas->pemasukan = $validatedData['pemasukan'];
            $bukuKas->pengeluaran = $validatedData['pengeluaran'];
            $bukuKas->saldototal = $saldoAkhir;
            $bukuKas->company = $employee->unit_bisnis;
            $bukuKas->office = $employee->divisi;
            $bukuKas->save();

            DB::commit();
            return redirect()->route('buku-kas.index')->with('success', 'Data berhasil disimpan!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage());
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
        $validatedData = $request->validate([
            'judul' => 'required|string|max:255',
            'desc' => 'nullable|string',
            'pemasukan' => 'required|numeric',
            'pengeluaran' => 'required|numeric',
        ]);
    
        DB::beginTransaction();
        try {
            $bukuKas = KasManagement::findOrFail($id);
            $office = $bukuKas->office;
    
            // Get the current saldo_akhir before the update
            $initialSaldoAkhir = $bukuKas->saldototal;
    
            // Calculate the adjustment needed based on the difference between old and new values
            $pemasukanDifference = $validatedData['pemasukan'] - $bukuKas->pemasukan;
            $pengeluaranDifference = $validatedData['pengeluaran'] - $bukuKas->pengeluaran;
    
            // Adjust the saldototal
            $newSaldoAkhir = $initialSaldoAkhir + $pemasukanDifference - $pengeluaranDifference;
    
            // Update the bukuKas record
            $bukuKas->judul = $validatedData['judul'];
            $bukuKas->desc = $validatedData['desc'];
            $bukuKas->pemasukan = $validatedData['pemasukan'];
            $bukuKas->pengeluaran = $validatedData['pengeluaran'];
            $bukuKas->saldototal = $newSaldoAkhir;
            $bukuKas->save();
    
            DB::commit();
            return redirect()->route('buku-kas.index')->with('success', 'Data berhasil diupdate!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan saat mengupdate data: ' . $e->getMessage());
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
        DB::beginTransaction();
        try {
            $bukuKas = KasManagement::findOrFail($id);
            $bukuKas->delete();

            DB::commit();
            return redirect()->route('buku-kas.index')->with('success', 'Data berhasil dihapus!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menghapus data: ' . $e->getMessage());
        }
    }
}
