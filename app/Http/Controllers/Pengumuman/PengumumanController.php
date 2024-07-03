<?php

namespace App\Http\Controllers\Pengumuman;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Employee;
use App\Organisasi\Organisasi;
use App\Pengumuman\Pengumuman;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PengumumanController extends Controller
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
        $organisasi = Organisasi::where('company', $company->unit_bisnis)->get();

        $pengumuman = Pengumuman::where('company', $company->unit_bisnis)->get();
        return view('pages.hc.pengumuman.index', compact('pengumuman','organisasi'));
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
        $company = Employee::where('nik', $code)->first();

        $request->validate([
            'judul' => 'required|string|max:255',
            'konten' => 'required|string',
            'publish_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:publish_date',
        ]);

        try {
            // Simpan pengumuman
            $pengumuman = new Pengumuman;
            $pengumuman->judul = $request->judul;
            $pengumuman->konten = $request->konten;
            $pengumuman->tujuan = $request->tujuan; // Mengubah array menjadi JSON string
            $pengumuman->publish_date = $request->publish_date;
            $pengumuman->end_date = $request->end_date;
            $pengumuman->company = $company->unit_bisnis;
            $pengumuman->save();

            return redirect()->route('pengumuman.index')->with('success', 'Pengumuman berhasil dibuat');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
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
        $pengumuman = Pengumuman::findOrFail($id);

        // Validasi bahwa user yang mengedit memiliki akses atau sesuai dengan perusahaan
        $user = Auth::user();
        $code = $user->employee_code;
        $company = Employee::where('nik', $code)->first();

        if (!$company || $pengumuman->company !== $company->unit_bisnis) {
            return redirect()->route('pengumuman.index')->with('error', 'Anda tidak memiliki akses untuk mengedit pengumuman ini.');
        }

        // Lakukan validasi input
        $request->validate([
            'judul' => 'required|string|max:255',
            'konten' => 'required|string',
            'publish_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:publish_date',
        ]);

        try {
            // Update pengumuman
            $pengumuman->judul = $request->judul;
            $pengumuman->konten = $request->konten;
            $pengumuman->tujuan = $request->tujuan; // Mengubah array menjadi JSON string
            $pengumuman->publish_date = $request->publish_date;
            $pengumuman->end_date = $request->end_date;
            $pengumuman->save();

            return redirect()->route('pengumuman.index')->with('success', 'Pengumuman berhasil diperbarui');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
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
        $pengumuman = Pengumuman::findOrFail($id);

        // Validasi bahwa user yang menghapus memiliki akses atau sesuai dengan perusahaan
        $user = Auth::user();
        $code = $user->employee_code;
        $company = Employee::where('nik', $code)->first();

        if (!$company || $pengumuman->company !== $company->unit_bisnis) {
            return redirect()->route('pengumuman.index')->with('error', 'Anda tidak memiliki akses untuk menghapus pengumuman ini.');
        }

        try {
            // Hapus pengumuman
            $pengumuman->delete();

            return redirect()->route('pengumuman.index')->with('success', 'Pengumuman berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->route('pengumuman.index')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
