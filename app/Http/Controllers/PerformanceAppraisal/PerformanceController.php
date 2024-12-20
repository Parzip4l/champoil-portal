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
use App\PerformanceAppraisal\PaModel;
use App\Setting\Golongan\GolonganModel;

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
        $level = GolonganModel::where('company', $company->unit_bisnis)->get();

        return view('pages.hc.pa.setting.kategori.index', compact('kategori','level'));
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
        $level = GolonganModel::where('company', $company->unit_bisnis)->get();

        return view('pages.hc.pa.setting.predikat.index', compact('predikat','level'));
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
        $level = GolonganModel::where('company', $company->unit_bisnis)->get();

        return view('pages.hc.pa.setting.faktor.index', compact('faktor','kategori','level'));
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

    public function duplicateFaktor($id)
    {
        // Temukan data asli berdasarkan ID
        $originalData = FaktorModel::find($id);

        if (!$originalData) {
            return redirect()->back()->with('error', 'Data tidak ditemukan');
        }

        try {
            // Duplikasi data
            $newData = $originalData->replicate();
            $newData->name = $originalData->name . ' (Copy)';
            $newData->save();

            return redirect()->route('faktor-pa.setting')->with('success', 'Faktor Data berhasil diduplikasi');
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

    // Performance Appraisal Index 
    public function indexPA()
    {
        $code = Auth::user()->employee_code;
        $company = Employee::where('nik', $code)->first();
        $dataLogin = json_decode(Auth::user()->permission);
        if (in_array('superadmin_access', $dataLogin)) {
            // Tampilkan semua data PA
            $padata =PaModel::where('company', $company->unit_bisnis)->get();
        } elseif (in_array('dashboard_access', $dataLogin)) {
            // Tampilkan data PA berdasarkan divisi
            $padata = PaModel::where('created_by',$company->nama)->get();
        } else {
            // Jika tidak memiliki akses, data kosong
            $padata = collect(); // Data kosong
        }
        return view('pages.hc.pa.index', compact('padata'));
    }

    public function AllPerformanceList()
{
    $code = Auth::user()->employee_code;
    $company = Employee::where('nik', $code)->first();
    $employees = Employee::where('unit_bisnis', $company->unit_bisnis)
        ->where('organisasi', 'Management Leaders')
        ->where('resign_status', '0')
        ->get();
    $predikats = PredikatModel::where('company', $company->unit_bisnis)->get();
    $performanceData = [];

    foreach ($employees as $employee) {
        $paData = PaModel::where('nik', $employee->nik)->get();
        $averageNilai = 0; // Nilai rata-rata PA
        $predikatName = null;
        $periode = null;
        $tahun = null;

        if (!$paData->isEmpty()) {
            // Hitung nilai rata-rata dari semua nilai_keseluruhan
            $averageNilai = $paData->avg(function ($pa) {
                return floatval($pa->nilai_keseluruhan);
            });

            // Cari predikat berdasarkan nilai rata-rata
            foreach ($predikats as $predikat) {
                $minNilai = floatval($predikat->min_nilai);
                $maxNilai = floatval($predikat->max_nilai);

                if ($averageNilai >= $minNilai && $averageNilai <= $maxNilai) {
                    $predikatName = $predikat->name;
                    break;
                }
            }

            // Ambil periode dan tahun dari data PA pertama
            $periode = $paData->first()->periode;
            $tahun = $paData->first()->tahun;
        }

        $performanceData[] = [
            'employee_name' => $employee->nama,
            'nik' => $employee->nik,
            'average_nilai' => $averageNilai,
            'predikat_name' => $predikatName,
            'periode' => $periode,
            'tahun' => $tahun,
        ];
    }

    // Urutkan berdasarkan nilai rata-rata dari tertinggi ke terendah
    usort($performanceData, function ($a, $b) {
        return $b['average_nilai'] <=> $a['average_nilai'];
    });

    return view('pages.hc.pa.ratarata', compact('performanceData'));
}



    public function createPA(Request $request)
    {
        $code = Auth::user()->employee_code;
        $company = Employee::where('nik', $code)->first();
        $dataAtasan = $company->manager;

        $kategoriPa = KategoriModel::where('company', $company->unit_bisnis)->where('level', $company->golongan)->get();
        $totalKategori = $kategoriPa->count();
        $faktor = FaktorModel::where('company', $company->unit_bisnis)->get();

        if($company->golongan === 'F') {
            $employee = Employee::where('unit_bisnis', $company->unit_bisnis)
                ->where('resign_status', 0)
                ->whereIn('golongan', ['D', 'E', 'F'])
                ->where('nik', '!=', $company->nik)
                ->get();
        }elseif($company->golongan === 'E' || $company->golongan === 'D'){
            $employee = Employee::where('unit_bisnis', $company->unit_bisnis)
                ->where('resign_status', 0)
                ->where(function ($query) use ($company) {
                    $query->whereIn('golongan', ['D', 'E', 'F'])
                        ->orWhere('divisi', $company->divisi);
                })
                ->where('nik', '!=', $company->nik)
                ->get();
        }else{
            $employee = Employee::where('unit_bisnis', $company->unit_bisnis)
            ->where('resign_status', 0)
            ->where('divisi',$company->divisi)
            ->where('nik', '!=', $company->nik)
            ->get();
        }

        return view('pages.hc.pa.create', compact('faktor','employee','kategoriPa','totalKategori'));
    }

    public function deletePA($id)
    {
        try{
            $padata = PaModel::findOrFail($id);
            $padata->delete();

            return redirect()->route('faktor-pa.setting')->with('success', 'Faktor data berhasil dihapus');
        }catch(\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function getTotalKategori($level)
    {
        $code = Auth::user()->employee_code;
        $company = Employee::where('nik', $code)->first();

        $totalKategori = KategoriModel::where('company', $company->unit_bisnis)
            ->where('level', $level)
            ->count();

        return response()->json(['totalKategori' => $totalKategori]);
    }

    public function getFactorsByLevel(Request $request)
    {
        try {
            $code = Auth::user()->employee_code;
            $company = Employee::where('nik', $code)->first();

            if (!$company) {
                return response()->json(['error' => 'Company not found'], 404);
            }

            $faktors = FaktorModel::where('company', $company->unit_bisnis)
                ->where('level', $request->level)
                ->get();

                return response()->json([
                    'faktors' => $faktors
                ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Server error: ' . $e->getMessage()], 500);
        }
    }

    public function storePerformance(Request $request)
    {

        $request->validate([
            'periode' => 'required|string|max:255',
        ]);

        try {
            // Mendapatkan kode karyawan dari Auth
            $code = Auth::user()->employee_code;
            $company = Employee::where('nik', $code)->first();
            
            // Mendapatkan data karyawan dari input
            $employeeData = Employee::where('nik', $request->nik)->first();

            // Membuat objek Performance Appraisal
            $pa = new PaModel();
            $pa->periode = $request->periode;
            $pa->tahun = $request->tahun;
            $pa->nik = $request->nik;
            $pa->name = $employeeData->nama;
            $pa->nilai_keseluruhan = $request->nilai_keseluruhan;
            $pa->komentar_masukan = $request->komentar_masukan;
            $pa->catatan_target = $request->catatan_target;
            $pa->approve_byemployee = 'false';
            $pa->created_by = $company->nama;
            $pa->company = $company->unit_bisnis;

            // Mengambil kategori dari database berdasarkan unit bisnis perusahaan
            $kategoriPa = KategoriModel::where('company', $company->unit_bisnis)->get();

            // Array untuk menyimpan data detail performance appraisal
            $detailsData = [];

            // Looping untuk setiap kategori
            foreach ($kategoriPa as $kategori) {
                // Mengambil faktor dari database berdasarkan kategori
                $faktors = FaktorModel::where('kategori', $kategori->name)->where('level', $request->level)->get();
                
                // Looping untuk setiap faktor dalam kategori
                foreach ($faktors as $faktor) {
                    
                    // Mengambil nilai dan keterangan dari request berdasarkan id faktor
                    $nilai = $request->nilai[$faktor->id] ?? null;
                    $keterangan = $request->keterangan[$faktor->id] ?? null;

                    // Menambahkan data faktor ke dalam array detailsData hanya jika nilai tidak null
                    $exists = false;
                    foreach ($detailsData as $detail) {
                        if ($detail['id'] === $faktor->id) {
                            $exists = true;
                            break;
                        }
                    }

                    // Menambahkan data faktor ke dalam array detailsData hanya jika nilai tidak null dan belum ada dalam array
                    if ($nilai !== null && !$exists) {
                        $detailsData[] = [
                            'id' => $faktor->id,
                            'kategori' => $kategori->name,
                            'name' => $faktor->name,
                            'deskripsi' => $faktor->deskripsi,
                            'bobot_nilai' => $faktor->bobot_nilai,
                            'nilai' => $nilai,
                            'keterangan' => $keterangan,
                        ];
                    }
                    
                }
            }
            // Encode detailsData menjadi JSON sebelum disimpan
            $pa->detailsdata = json_encode($detailsData);
            
            // Menyimpan data ke database
            $pa->save();

            return redirect()->route('index.pa')->with('success', 'Performance Appraisal berhasil dibuat.');
        } catch (\Exception $e) {
            // Menangani kesalahan jika gagal menyimpan
            return back()->with('error', 'Gagal menyimpan Performance Appraisal: ' . $e->getMessage());
        }
    }


    public function editPerformance($id)
    {
        $code = Auth::user()->employee_code;
        $company = Employee::where('nik', $code)->first();
        // Ambil data performa berdasarkan ID
        $performance = PaModel::findOrFail($id);

        // Ambil daftar karyawan untuk dropdown
        $employee = Employee::all();
        $nikPemilikPa = $performance->nik;

        $levelPemilik = Employee::where('nik',$nikPemilikPa)->first();
        
        // Ambil daftar kategori PA untuk ditampilkan di form
        $kategoriPa = KategoriModel::where('company', $company->unit_bisnis)->where('level',$levelPemilik->golongan)->get();
        $totalKategori = $kategoriPa->count();
        $faktor = FaktorModel::where('company', $company->unit_bisnis)
        ->get();
        // Tampilkan view edit dengan data yang diperlukan
        return view('pages.hc.pa.edit', compact('performance', 'employee', 'kategoriPa','totalKategori','faktor'));
    }
    
    public function updatePerformance(Request $request, $id)
    {
        $request->validate([
            'periode' => 'required|string|max:255',
        ]);

        try {
            // Mendapatkan kode karyawan dari Auth
            $code = Auth::user()->employee_code;
            $company = Employee::where('nik', $code)->first();

            // Mendapatkan data karyawan dari input
            $employeeData = Employee::where('nik', $request->nik)->first();

            // Mengambil data Performance Appraisal yang akan diupdate
            $pa = PaModel::findOrFail($id);

            // Update data Performance Appraisal
            $pa->periode = $request->periode;
            $pa->tahun = $request->tahun;
            $pa->nik = $request->nik;
            $pa->name = $employeeData->nama;
            $pa->nilai_keseluruhan = $request->nilai_keseluruhan;
            $pa->komentar_masukan = $request->komentar_masukan;
            $pa->catatan_target = $request->catatan_target;
            // Jika perlu, tambahkan logika untuk field lainnya

            // Mengambil kategori dari database berdasarkan unit bisnis perusahaan
            $kategoriPa = KategoriModel::where('company', $company->unit_bisnis)->get();

            // Array untuk menyimpan data detail performance appraisal
            $detailsData = [];

            // Looping untuk setiap kategori
            foreach ($kategoriPa as $kategori) {
                // Mengambil faktor dari database berdasarkan kategori
                $faktors = FaktorModel::where('kategori', $kategori->name)->get();

                // Looping untuk setiap faktor dalam kategori
                foreach ($faktors as $faktor) {
                    // Mengambil nilai dan keterangan dari request berdasarkan id faktor
                    $nilai = $request->nilai[$faktor->id] ?? null;
                    $keterangan = $request->keterangan[$faktor->id] ?? null;

                    // Menambahkan data faktor ke dalam array detailsData
                    $detailsData[] = [
                        'id' => $faktor->id,
                        'kategori' => $kategori->name,
                        'name' => $faktor->name,
                        'deskripsi' => $faktor->deskripsi,
                        'bobot_nilai' => $faktor->bobot_nilai,
                        'nilai' => $nilai,
                        'keterangan' => $keterangan,
                    ];
                }
            }

            // Encode detailsData menjadi JSON sebelum disimpan
            $pa->detailsdata = json_encode($detailsData);

            // Menyimpan perubahan ke database
            $pa->save();

            return redirect()->route('index.pa')->with('success', 'Performance Appraisal berhasil diupdate.');
        } catch (\Exception $e) {
            // Menangani kesalahan jika gagal menyimpan
            return back()->with('error', 'Gagal mengupdate Performance Appraisal: ' . $e->getMessage());
        }
    }

    public function MyPerformanceList()
    {
        $code = Auth::user()->employee_code;
        $employee = Employee::where('nik', $code)->first();
        $predikats = PredikatModel::where('company', $employee->unit_bisnis)->get();
        $paData = PaModel::where('nik', $code)->get();

        // Periksa apakah data PA ada
        $hasData = !$paData->isEmpty();

        $predikatName = 0;
        $averageNilai = 0; // Nilai rata-rata PA

        if ($hasData) {
            // Hitung nilai rata-rata dari semua nilai_keseluruhan
            $averageNilai = $paData->avg(function ($pa) {
                return floatval($pa->nilai_keseluruhan);
            });

            foreach ($paData as $pa) {
                $nilai_keseluruhan = floatval($pa->nilai_keseluruhan);
                $predikatName = null; // Reset predikat untuk setiap data

                foreach ($predikats as $predikat) {
                    $minNilai = floatval($predikat->min_nilai);
                    $maxNilai = floatval($predikat->max_nilai);

                    if ($nilai_keseluruhan >= $minNilai && $nilai_keseluruhan <= $maxNilai) {
                        $predikatName = $predikat->name;
                        break;
                    }
                }
                $pa->predikat_name = $predikatName;
            }
        }

        return view('pages.hc.pa.myperformance', compact('paData', 'employee', 'hasData', 'predikatName', 'averageNilai'));
    }




    public function DetailPerformance($id)
    {
        $code = Auth::user()->employee_code;
        $company = Employee::where('nik', $code)->first();
        // Ambil data performa berdasarkan ID
        $performance = PaModel::findOrFail($id);

        // Ambil daftar karyawan untuk dropdown
        $employee = Employee::all();

        // Ambil daftar kategori PA untuk ditampilkan di form
        $kategoriPa = KategoriModel::where('company', $company->unit_bisnis)->get();
        $totalKategori = $kategoriPa->count();

        $faktor = FaktorModel::where('company', $company->unit_bisnis)
        ->get();
        // Tampilkan view edit dengan data yang diperlukan
        return view('pages.hc.pa.details', compact('performance', 'employee', 'kategoriPa','totalKategori','faktor'));
    }

    public function approvePa($id)
    {
        // Cari data berdasarkan id
        $performance = PaModel::find($id);

        // Pastikan data ditemukan
        if ($performance) {
            // Ubah nilai approve_byemployee menjadi true
            $performance->approve_byemployee = 'true';
            $performance->save();
        }

        // Kembali ke halaman sebelumnya atau ke halaman lain
        return redirect()->back()->with('success', 'Tanda tangan berhasil.');
    }
}
