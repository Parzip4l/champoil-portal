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
use Illuminate\Support\Facades\Storage;
use App\Slack;

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
            if ($request->hasFile('attachments')) {
                $file = $request->file('attachments');
                
                // Mendapatkan ekstensi file
                $extension = $file->getClientOriginalExtension();
            
                // Mengecek apakah file adalah PDF atau JPG
                if ($extension !== 'pdf' && $extension !== 'jpg') {
                    return redirect()->back()->with('error', 'Hanya file PDF dan JPG yang diizinkan.');
                }
            
                // Jika file adalah PDF atau JPG maka simpan
                $path = $file->store('public/files');
                $pengumuman->attachments = $path;
            }
            $pengumuman->save();

            if ($company->unit_bisnis === 'CHAMPOIL') {
                $slackChannel = Slack::where('channel', 'General')->first();
                $slackWebhookUrl = $slackChannel->url;
                $today = now()->toDateString();
                $data = [
                    'text' => "Ada Pengumuman Baru Telah Terbit Di Aplikasi TRUEST",
                    'attachments' => [
                        [
                            'fields' => [
                                [
                                    'title' => 'Judul',
                                    'value' => $request->judul,
                                    'short' => true,
                                ],
                                [
                                    'title' => 'Tujuan',
                                    'value' => $request->tujuan,
                                    'short' => true,
                                ],
                                [
                                    'title' => 'Untuk Lihat Detail Silahkan Buka Aplikasi TRUEST',
                                    'short' => true,
                                ]
                            ],
                        ],
                    ],
                    
                ];

                $data_string = json_encode($data);

                $ch = curl_init($slackWebhookUrl);
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, [
                    'Content-Type: application/json',
                    'Content-Length: ' . strlen($data_string),
                ]);

                $result = curl_exec($ch);

                if ($result === false) {
                    // Penanganan kesalahan jika Curl gagal
                    $error = curl_error($ch);
                    // Handle the error here
                    return redirect()->back()->with('error', 'Terjadi kesalahan saat mengirim data ke Slack: ' . $error);
                }

                $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

                if ($httpCode !== 200) {
                    // Penanganan kesalahan jika Slack merespons selain status 200 OK
                    // Handle the error here
                    return redirect()->back()->with('error', 'Terjadi kesalahan saat mengirim data ke Slack. Kode status: ' . $httpCode);
                }

                curl_close($ch);
            }

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
        $code = Auth::user()->employee_code;
        $company = Employee::where('nik', $code)->first();

        $request->validate([
            'judul' => 'required|string|max:255',
            'konten' => 'required|string',
            'publish_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:publish_date',
        ]);

        try {
            $pengumuman = Pengumuman::findOrFail($id);
            $pengumuman->judul = $request->judul;
            $pengumuman->konten = $request->konten;
            $pengumuman->tujuan = $request->tujuan; // Mengubah array menjadi JSON string
            $pengumuman->publish_date = $request->publish_date;
            $pengumuman->end_date = $request->end_date;
            $pengumuman->company = $company->unit_bisnis;

            if ($request->hasFile('attachments')) {
                $file = $request->file('attachments');

                // Mendapatkan ekstensi file
                $extension = $file->getClientOriginalExtension();

                // Mengecek apakah file adalah PDF atau JPG
                if ($extension !== 'pdf' && $extension !== 'jpg') {
                    return redirect()->back()->with('error', 'Hanya file PDF dan JPG yang diizinkan.');
                }

                // Jika ada lampiran sebelumnya, hapus file lama
                if ($pengumuman->attachments) {
                    Storage::delete($pengumuman->attachments);
                }

                // Simpan file baru
                $path = $file->store('public/files');
                $pengumuman->attachments = $path;
            }

            $pengumuman->save();

            return redirect()->route('pengumuman.index')->with('success', 'Pengumuman berhasil diperbarui');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function downloadAttachment($id)
    {
        try {
            $pengumuman = Pengumuman::findOrFail($id);

            if ($pengumuman->attachments) {
                return Storage::download($pengumuman->attachments);
            } else {
                return redirect()->back()->with('error', 'No attachment found.');
            }
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
