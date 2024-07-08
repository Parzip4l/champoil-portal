<?php

namespace App\Http\Controllers\PerformanceAppraisal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

// Model
use App\Employee;
use App\Organisasi\Organisasi;
use App\PerformanceAppraisal\FaktorModel;
use App\PerformanceAppraisal\KategoriModel;
use App\PerformanceAppraisal\PredikatModel;

class PerformanceController extends Controller
{
    public function setting()
    {
        return view ('pages.hc.pa.setting.index');
    }

    public function IndexsettingKategori()
    {
        $code = Auth::user()->employee_code;
        $company = Employee::where('nik', $code)->first();

        $kategori = KategoriModel::where('company', $company->unit_bisnis)->get();

        return view('pages.hc.pa.setting.kategori.index', compact('kategori'));
    }

    public function storeKategori(Request $request)
    {
        $code = Auth::user()->employee_code;
        $company = Employee::where('nik', $code)->first();

        $request->validate([
            'name' => 'required|string|max:255',
            'level' => 'required|string',
        ]);

        try {
            // Simpan pengumuman
            $kategori = new KategoriModel;
            $kategori->name = $request->name;
            $kategori->level = $request->level;
            $kategori->company = $company->unit_bisnis;
            $kategori->save();

            return redirect()->route('kategori-pa.setting')->with('success', 'Kategori berhasil dibuat');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function updateKategori(Request $request, $id)
    {
        $code = Auth::user()->employee_code;
        $company = Employee::where('nik', $code)->first();

        $request->validate([
            'name' => 'required|string|max:255',
            'level' => 'required|string',
        ]);

        try {
            $kategori = KategoriModel::findOrFail($id);
            $kategori->name = $request->name;
            $kategori->level = $request->level;
            $kategori->save();

            return redirect()->route('kategori-pa.setting')->with('success', 'Kategori berhasil diperbarui');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function deleteKategori($id)
    {
        try{
            $kategori = KategoriModel::findOrFail($id);
            $kategori->delete();

            return redirect()->route('kategori-pa.setting')->with('success', 'Kategori berhasil dihapus');
        }catch(\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // Predikat
    public function IndexsettingPredikat()
    {
        $code = Auth::user()->employee_code;
        $company = Employee::where('nik', $code)->first();

        $predikat = PredikatModel::where('company', $company->unit_bisnis)->get();

        return view('pages.hc.pa.setting.predikat.index', compact('predikat'));
    }

    public function storePredikat(Request $request)
    {
        $code = Auth::user()->employee_code;
        $company = Employee::where('nik', $code)->first();
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        try {
            // Simpan pengumuman
            $predikat = new PredikatModel;
            $predikat->name = $request->name;
            $predikat->min_nilai = $request->min_nilai;
            $predikat->max_nilai = $request->max_nilai;
            $predikat->company = $company->unit_bisnis;
            $predikat->save();

            return redirect()->route('predikat-pa.setting')->with('success', 'Predikat Nilai berhasil dibuat');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function updatePredikat(Request $request, $id)
    {
        $code = Auth::user()->employee_code;
        $company = Employee::where('nik', $code)->first();

        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        try {
            $predikat = PredikatModel::findOrFail($id);
            $predikat->name = $request->name;
            $predikat->min_nilai = $request->min_nilai;
            $predikat->max_nilai = $request->max_nilai;
            $predikat->save();

            return redirect()->route('predikat-pa.setting')->with('success', 'Predikat Nilai berhasil diperbarui');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function deletePredikat($id)
    {
        try{
            $predikat = PredikatModel::findOrFail($id);
            $predikat->delete();

            return redirect()->route('predikat-pa.setting')->with('success', 'Predikat berhasil dihapus');
        }catch(\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // Faktor
    public function IndexsettingFaktor()
    {
        $code = Auth::user()->employee_code;
        $company = Employee::where('nik', $code)->first();

        $faktor = FaktorModel::where('company', $company->unit_bisnis)->get();
        $kategori = KategoriModel::where('company', $company->unit_bisnis)->get();

        return view('pages.hc.pa.setting.faktor.index', compact('faktor','kategori'));
    }

    public function storeFaktor(Request $request)
    {
        $code = Auth::user()->employee_code;
        $company = Employee::where('nik', $code)->first();
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        try {
            // Simpan pengumuman
            $faktor = new FaktorModel;
            $faktor->name = $request->name;
            $faktor->deskripsi = $request->deskripsi;
            $faktor->kategori = $request->kategori;
            $faktor->bobot_nilai = $request->bobot_nilai;
            $faktor->level = $request->level;
            $faktor->company = $company->unit_bisnis;
            $faktor->is_active = 'aktif';
            $faktor->save();

            return redirect()->route('faktor-pa.setting')->with('success', 'Faktor Data berhasil dibuat');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function updateFaktor(Request $request, $id)
    {
        $code = Auth::user()->employee_code;
        $company = Employee::where('nik', $code)->first();

        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        try {
            $faktor = FaktorModel::findOrFail($id);
            $faktor->name = $request->name;
            $faktor->deskripsi = $request->deskripsi;
            $faktor->kategori = $request->kategori;
            $faktor->level = $request->level;
            $faktor->bobot_nilai = $request->bobot_nilai;
            $faktor->save();

            return redirect()->route('faktor-pa.setting')->with('success', 'Faktor Data berhasil diperbarui');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function deleteFaktor($id)
    {
        try{
            $faktor = FaktorModel::findOrFail($id);
            $faktor->delete();

            return redirect()->route('faktor-pa.setting')->with('success', 'Faktor data berhasil dihapus');
        }catch(\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function updateStatusFaktor(Request $request, $id)
    {
        $code = Auth::user()->employee_code;
        $company = Employee::where('nik', $code)->first();
        
        // Validasi input
        $request->validate([
            'is_active' => 'required|in:aktif,nonaktif',
        ]);

        // Temukan data berdasarkan ID
        $data = FaktorModel::find($id);

        if (!$data) {
            return response()->json(['success' => false, 'message' => 'Data not found'], 404);
        }

        // Perbarui status aktif
        $data->is_active = $request->input('is_active');
        $data->save();

        return response()->json(['success' => true, 'message' => 'Status updated successfully']);
    }
}
