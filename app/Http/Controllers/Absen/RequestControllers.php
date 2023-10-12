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

        $requestabsen = RequestAbsen::findOrFail($id);
        if ($requestabsen->aprrove_status !== 'Approved') {
            $requestabsen->aprrove_status = 'Approved';
            $requestabsen->aprroved_by = $EmployeeCode;
            $requestabsen->save();

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

        return redirect()->route('attendence-request.index')->with('success', 'Data Pengajuan Berhasil Diupdate.');
    }

    public function updateStatusReject($id)
    {
        $userId = Auth::id();
        $EmployeeCode = Auth::user()->employee_code;

        $requestabsen = RequestAbsen::findOrFail($id);
        if ($requestabsen->aprrove_status !== 'Reject') {
            $requestabsen->aprrove_status = 'Reject';
            $requestabsen->aprroved_by = $EmployeeCode;
            $requestabsen->save();
        }

        return redirect()->route('attendence-request.index')->with('success', 'Data Pengajuan Berhasil Diupdate.');
    }

    public function download($id)
    {
        $requestabsen = RequestAbsen::findOrFail($id);

        $file_path = storage_path('app/' .$requestabsen->dokumen);

        return response()->download($file_path);
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

        $historyData = RequestAbsen::where('employee', $EmployeeCode)->get();

        return view('pages.absen.request.create', compact('EmployeeCode','historyData'));
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
        $pengajuan = new RequestAbsen();
        $pengajuan->tanggal = $request->input('tanggal');
        $pengajuan->employee = $request->input('employee');
        $pengajuan->status = $request->input('status');
        $pengajuan->alasan = $request->input('alasan');
        $pengajuan->aprrove_status = $request->input('aprrove_status');
        if ($request->hasFile('dokumen')) {
            $file = $request->file('dokumen');

            // Check if the uploaded file is a PDF
            if ($file->getClientOriginalExtension() !== 'pdf') {
                throw ValidationException::withMessages(['dokumen' => 'Hanya file PDF yang diizinkan.']);
            }

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
