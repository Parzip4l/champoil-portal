<?php

namespace App\Http\Controllers\News;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Employee;
use App\News\News;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class NewsController extends Controller
{
    public function index()
    {
        $code = Auth::user()->employee_code;
        $company = Employee::where('nik', $code)->first();

        $news = News::where('company', $company->unit_bisnis)->get();

        return view('pages.hc.news.index', compact('news'));
    }

    public function create()
    {
        return view ('pages.hc.news.create');
    }

    public function store(Request $request)
    {
        $code = Auth::user()->employee_code;
        $company = Employee::where('nik', $code)->first();

        $request->validate([
            'judul' => 'required|string|max:255',
            'excerpt' => 'required|string'
        ]);
        
        try {
            // Simpan pengumuman
            $news = new News;
            $news->judul = $request->judul;
            $news->konten = $request->konten;
            $news->excerpt = $request->excerpt;
            $news->author = $company->nama;
            $news->company = $company->unit_bisnis;
            if ($request->hasFile('featuredimage')) {
                $photo = $request->file('featuredimage');
                $photoFileName = time() . '.' . $photo->getClientOriginalExtension();
                $photo->move(public_path('/images/featuredimage'), $photoFileName);
                
                // Delete old photo if exists
                if ($news->featuredimage) {
                    $oldPhotoPath = public_path('/images/featuredimage') . $employee->photo;
                    if (file_exists($oldPhotoPath)) {
                        unlink($oldPhotoPath);
                    }
                }
                $news->featuredimage = $photoFileName;
            }
            $news->save();

            return redirect()->route('news.index')->with('success', 'Berita berhasil dibuat');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        try {
            // Menggunakan findOrFail untuk mencari berita berdasarkan id
            $news = News::findOrFail($id);

            // Kembalikan tampilan atau data yang diinginkan
            return view('pages.hc.news.edit', compact('news'));
        } catch (ModelNotFoundException $e) {
            // Jika berita tidak ditemukan, kembali ke halaman sebelumnya dengan pesan kesalahan
            return redirect()->back()->withErrors('News not found.');
        }
    }

    public function show($id)
    {
        try {
            // Menggunakan findOrFail untuk mencari berita berdasarkan id
            $news = News::findOrFail($id);

            // Kembalikan tampilan atau data yang diinginkan
            return view('pages.hc.news.single', compact('news'));
        } catch (ModelNotFoundException $e) {
            // Jika berita tidak ditemukan, kembali ke halaman sebelumnya dengan pesan kesalahan
            return redirect()->back()->withErrors('News not found.');
        }
    }

    public function update(Request $request, $id)
    {
        $code = Auth::user()->employee_code;
        $company = Employee::where('nik', $code)->first();

        $request->validate([
            'judul' => 'required|string|max:255',
            'excerpt' => 'required|string'
        ]);
        
        try {
            // Temukan berita berdasarkan ID
            $news = News::findOrFail($id);
            $news->judul = $request->judul;
            $news->konten = $request->konten;
            $news->excerpt = $request->excerpt;
            $news->author = $company->nama;
            $news->company = $company->unit_bisnis;

            if ($request->hasFile('featuredimage')) {
                $file = $request->file('featuredimage');
                
                // Mendapatkan ekstensi file
                $extension = $file->getClientOriginalExtension();
                
                // Mengecek apakah file adalah PNG atau JPG
                if ($extension !== 'png' && $extension !== 'jpg' && $extension !== 'jpeg') {
                    return redirect()->back()->with('error', 'Hanya file PNG dan JPG yang diizinkan.');
                }
                
                // Hapus gambar lama jika ada
                if ($news->featuredimage) {
                    Storage::delete($news->featuredimage);
                }
                
                // Simpan gambar baru
                $path = $file->store('public/newsimage');
                $news->featuredimage = $path;
            }
            
            $news->save();

            return redirect()->route('news.index')->with('success', 'Berita berhasil diperbarui');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
