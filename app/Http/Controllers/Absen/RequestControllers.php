<?php

namespace App\Http\Controllers\Absen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Absen;
use App\Employee;
use App\ModelCG\Schedule;
use App\ModelCG\ScheduleBackup;
use App\ModelCG\Project;
use App\User;
use App\Absen\RequestAbsen;
use Carbon\Carbon;
use App\Absen\RequestType;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RequestControllers extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $dataRequest = RequestAbsen::all();
        return view('pages.absen.request.index', compact('dataRequest'));
    }

    public function updateStatusSetuju($id)
    {
        $userId = Auth::id();
        $EmployeeCode = Auth::user()->employee_code;

        $requestabsen = RequestAbsen::where('unik_code', $id)->firstOrFail();
        if ($requestabsen->aprrove_status !== 'Approved') {
            $requestabsen->aprrove_status = 'Approved';
            $requestabsen->aprroved_by = $EmployeeCode;
            $requestabsen->save();

            // Cek status jika lupa absen
            if ($requestabsen->status = 'F') {
                // Simpan Kedalam Table Absen
                $absen = new Absen();
                $absen->user_id = $EmployeeCode;
                $absen->nik = $EmployeeCode;
                $absen->tanggal = $requestabsen->tanggal;
                $absen->clock_in = $requestabsen->clock_in;
                $absen->clock_out = $requestabsen->clock_out;
                $absen->latitude = '-';
                $absen->longtitude = '-';
                $absen->status = $requestabsen->status;
                $absen->save();
            } else {
                // Simpan Kedalam Table Absen
                $absen = new Absen();
                $absen->user_id = $EmployeeCode;
                $absen->nik = $EmployeeCode;
                $absen->tanggal = $requestabsen->tanggal;
                $absen->clock_in = '-';
                $absen->latitude = '-';
                $absen->longtitude = '-';
                $absen->status = $requestabsen->status;
                $absen->save();
            }
            
        }

        return redirect()->back()->with('success', 'Data Pengajuan Berhasil Diupdate.');
    }

    public function updateStatusReject($id)
    {
        $userId = Auth::id();
        $EmployeeCode = Auth::user()->employee_code;

        $requestabsen = RequestAbsen::where('unik_code', $id)->firstOrFail();
        if ($requestabsen->aprrove_status !== 'Reject') {
            $requestabsen->aprrove_status = 'Reject';
            $requestabsen->aprroved_by = $EmployeeCode;
            $requestabsen->save();
        }

        return redirect()->back()->with('success', 'Data Pengajuan Berhasil Diupdate.');
    }

    public function download($id)
    {
        try {
            $requestabsen = RequestAbsen::where('unik_code', $id)->firstOrFail();
        
            $file_path = storage_path('app/' . $requestabsen->dokumen);
        
            return response()->download($file_path);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            // Handle the case when no data is found
            return redirect()->back()->with('error','Data not found.');
        } catch (\Exception $e) {
            // Handle other exceptions
            return redirect()->back()->with('error','Data not found.');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $userId = Auth::id();
        $EmployeeCode = Auth::user()->employee_code;
        $company = Employee::where('nik',$EmployeeCode)->select('unit_bisnis')->first();

        $historyData = RequestAbsen::where('employee', $EmployeeCode)->get();
        $typeRequest = RequestType::where('company', $company->unit_bisnis)->get();

        return view('pages.absen.request.create', compact('EmployeeCode','historyData','typeRequest'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required'
        ]);
        $randomNumber = mt_rand(100000, 999999);
        $pengajuan = new RequestAbsen();
        $pengajuan->unik_code = $randomNumber;
        $pengajuan->tanggal = $request->input('tanggal');
        $pengajuan->employee = $request->input('employee');
        $pengajuan->clock_in = $request->input('clock_in');
        $pengajuan->clock_out = $request->input('clock_out');
        $pengajuan->status = $request->input('status');
        $pengajuan->alasan = $request->input('alasan');
        $pengajuan->aprrove_status = $request->input('aprrove_status');
        if ($request->hasFile('dokumen')) {
            $file = $request->file('dokumen');
            
            // Mendapatkan ekstensi file
            $extension = $file->getClientOriginalExtension();
        
            // Mengecek apakah file adalah PDF atau JPG
            if ($extension !== 'pdf' && $extension !== 'jpg') {
                return redirect()->back()->with('error', 'Hanya file PDF dan JPG yang diizinkan.');
            }
        
            // Jika file adalah PDF atau JPG maka simpan
            $path = $file->store('public/files');
            $pengajuan->dokumen = $path;
        }
        
        $pengajuan->save();

        return redirect()->back()->with('success', 'Pengajuan Berhasil Diajukan');
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
