<?php

namespace App\Http\Controllers\CgControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\ModelCG\Payroll;
use App\Employee;
use App\Absen;
use Carbon\Carbon;
use App\ModelCG\Project;
use App\ModelCG\ProjectDetails;

class PayrolNS extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $dataPayroll = Payroll::all();

        return view('pages.hc.kas.payroll.index',compact('dataPayroll'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $today = now();
        $start_date = $today->day >= 21 ? $today->copy()->day(21) : $today->copy()->subMonth()->day(21);
        $end_date = $today->day >= 21 ? $today->copy()->addMonth()->day(20) : $today->copy()->day(20);

        $start_date2 = Carbon::parse($start_date)->format('d-m-Y');
        $end_date2 = Carbon::parse($end_date)->format('d-m-Y');

        $date_range = [];
        $current_date = $start_date->copy();

        while ($current_date->lte($end_date)) {
            $date_range[] = $current_date->copy();
            $current_date->addDay();
        }

        $dates_for_form = [];
        foreach ($date_range as $date) {
            $dates_for_form[$date->toDateString()] = $date->format('d F Y');
        }

        $employee = Employee::all();
        return view('pages.hc.kas.payroll.create', compact('employee','start_date2','end_date2','start_date','end_date'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validasi request
        $request->validate([
            'month' => 'required',
            'year' => 'required',
            'periode' => 'required',
            'unit_bisnis' => 'required',
        ]);

        $periodeDates = explode(' - ', $request->periode);
        $startDate = \Carbon\Carbon::createFromFormat('d-m-Y', $periodeDates[0])->format('Y-m-d');
        $endDate = \Carbon\Carbon::createFromFormat('d-m-Y', $periodeDates[1])->format('Y-m-d');

        foreach ($request->employee_code as $nik) {
            // Dapatkan data karyawan
            $employee = Employee::where('nik', $nik)->first();
            $jabatan = $employee->jabatan;

            // Dapatkan data absen berdasarkan range periode dan nik karyawan
            $absen = Absen::where('nik', $nik)
                ->whereBetween('tanggal', [$startDate, $endDate])
                ->get();

            $totalHari = 0;
            $totalGaji = 0;
            $totalHariBackup = 0;
            $totalGajiBackup = 0;
            $allowenceData= [];

            // Mengakumulasi jumlah hari dan total gaji dari setiap absensi
            foreach ($absen as $absensi) {

                $tanggalAbsensi = \Carbon\Carbon::createFromFormat('Y-m-d', $absensi->tanggal);
                $inPeriode = $tanggalAbsensi->isBetween($startDate, $endDate);

                if ($inPeriode) {
                    // Jika absensi berada dalam periode, tambahkan jumlah hari
                    $totalHari++;
                }

                if (!empty($absensi->project_backup)) {
                    // Jika ada project_backup, tambahkan jumlah hari backup
                    $totalHariBackup++;
                }

                // Ambil ID proyek dari kolom project dan project_backup
                $projectIds = [$absensi->project];
                $projectBackup = [$absensi->project_backup];

                // Dapatkan data project details dan rate harian dari project
                $projectDetails = ProjectDetails::whereIn('project_code', $projectIds)
                    ->where('jabatan', $jabatan) 
                    ->pluck('rate_harian', 'project_code');

                $projectDetailsBackup = ProjectDetails::whereIn('project_code', $projectBackup)
                    ->where('jabatan', $jabatan) 
                    ->pluck('rate_harian', 'project_code');

                // Hitung gaji untuk setiap proyek
                foreach ($projectIds as $projectId) {
                    if (isset($projectDetails[$projectId])) {
                        // Jika absensi berada dalam periode, tambahkan gaji
                        $rate_harian = $projectDetails[$projectId];
                        $totalGaji += $inPeriode ? $projectDetails[$projectId] : 0;
                    }
                }
                
                foreach ($projectBackup as $projectIdBackup) {
                    if (isset($projectDetailsBackup[$projectIdBackup])) {
                        // Jika absensi berada dalam periode, tambahkan gaji backup
                        $totalGajiBackup += $inPeriode ? $projectDetailsBackup[$projectIdBackup] : 0;
                    }
                }
                $thp = $totalGaji + $totalGajiBackup;

                $allowenceData = [
                    'totalHari' => $totalHari,
                    'totalHariBackup' => $totalHariBackup, // Menambahkan total hari backup
                    'totalGaji' => $totalGaji,
                    'totalGajiBackup' => $totalGajiBackup,
                    'rate_harian' => $rate_harian
                ];

                $allowenceData = json_encode($allowenceData);
            }
            // Simpan data ke tabel payroll
            $payroll = new Payroll();
            $payroll->employee_code = $nik;
            $payroll->periode = $request->periode;
            $payroll->thp = $thp;
            $payroll->allowences = $allowenceData;
            $payroll->save();
        }

        // Redirect atau tampilkan pesan sukses
        return redirect()->route('payroll-kas.index')->with('success', 'Data payroll berhasil disimpan.');
    }

    public function getEmployees(Request $request) {
        $unitBisnis = $request->input('unit_bisnis');
        // Ambil daftar karyawan berdasarkan unit bisnis
        $employees = Employee::where('unit_bisnis', $unitBisnis)
                     ->where('organisasi', 'Frontline Officer') 
                     ->get();

        return response()->json(['employees' => $employees]);
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
