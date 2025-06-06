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
    public function index(Request $request)
    {
        $userId = Auth::id();
        $EmployeeCode = Auth::user()->employee_code;
        $company = Employee::where('nik', $EmployeeCode)->first();

        $dataRequest = [];

        if ($request->input('tanggal') != null) {
            $tanggalRange = explode(' to ', $request->input('tanggal')); // Example: ?tanggal=2025-04-21+to+2025-05-15

            if ($company->organisasi == 'Frontline Officer' || $company->organisasi == 'FRONTLINE OFFICER') {
                $get_project = Schedule::where('employee', $EmployeeCode)->where('tanggal',date('Y-m-d'))->first();
                $request_absen = RequestAbsen::join('karyawan', 'karyawan.nik', '=', 'requests_attendence.employee')
                                            ->where('karyawan.unit_bisnis', $company->unit_bisnis)
                                            ->whereBetween('requests_attendence.tanggal', [$tanggalRange[0], $tanggalRange[1]])
                                            ->select('requests_attendence.*')
                                            ->orderBy('requests_attendence.tanggal', 'desc')
                                            // ->limit(500)
                                            ->get();
                $dataRequest = [];
                if ($request_absen) {
                    foreach ($request_absen as $row) {
                        $cek = Schedule::whereBetween('schedules.tanggal', [$tanggalRange[0], $tanggalRange[1]])
                                ->where('project', $get_project->project)
                                ->where('employee', $row->employee)
                                ->count();
                        if ($cek > 0) {
                            $dataRequest[] = $row;
                        }
                    }
                }
            } else {
                $dataRequest = RequestAbsen::join('karyawan', 'karyawan.nik', '=', 'requests_attendence.employee')
                                ->where('karyawan.unit_bisnis', $company->unit_bisnis)
                                ->whereBetween('requests_attendence.tanggal', [$tanggalRange[0], $tanggalRange[1]])
                                ->select('requests_attendence.*')
                                ->orderBy('requests_attendence.tanggal', 'desc')
                                // ->limit(50)
                                ->get();
            }
        }
        
        return view('pages.absen.request.index', compact('dataRequest'));
    }

    public function updateStatusSetuju($id)
    {
        $userId = Auth::id();
        $EmployeeCode = Auth::user()->employee_code;

        $requestabsen = RequestAbsen::where('id', $id)->firstOrFail();
        if ($requestabsen->aprrove_status !== 'Approved') {
            $requestabsen->aprrove_status = 'Approved';
            $requestabsen->aprroved_by = $EmployeeCode;
            $requestabsen->save();

            $dataKaryawanRequest = $requestabsen->employee;
            $schedule = Schedule::where('employee', $dataKaryawanRequest)->where('tanggal', $requestabsen->tanggal)->first();
            
            // Cek status jika lupa absen
            if ($requestabsen->status = 'F') {
                // Simpan Kedalam Table Absen
                $absen = new Absen();
                $absen->user_id = $dataKaryawanRequest;
                $absen->nik = $dataKaryawanRequest;
                $absen->project = $schedule->project ?? '';
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
                $absen->user_id = $dataKaryawanRequest;
                $absen->nik = $dataKaryawanRequest;
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

        $requestabsen = RequestAbsen::where('id', $id)->firstOrFail();
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
            $requestabsen = RequestAbsen::where('id', $id)->firstOrFail();
        
            $file_path = storage_path('app/' . $requestabsen->dokumen);
        
            return response()->download($file_path);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            // Handle the case when no data is found
            return redirect()->back()->with('error', 'Data not found.');
        } catch (\Exception $e) {
            // Handle other exceptions
            return redirect()->back()->with('error', 'Data not found.');
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
        $company = Employee::where('nik', $EmployeeCode)->select('unit_bisnis')->first();

        $historyData = RequestAbsen::where('employee', $EmployeeCode)->get();
        $typeRequest = RequestType::where('company', $company->unit_bisnis)->get();

        return view('pages.absen.request.create', compact('EmployeeCode', 'historyData', 'typeRequest'));
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
        $pengajuan->jam_lembur = $request->input('jam_lembur');
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
