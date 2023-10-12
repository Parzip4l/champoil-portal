<?php

namespace App\Http\Controllers\CgControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\ModelCG\Schedule;
use App\ModelCG\ScheduleBackup;
use App\ModelCG\Shift;
use App\Employee;
use App\ModelCG\Project;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ScheduleBackupControllers extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $schedulesByProject = ScheduleBackup::all();
        return view('pages.hc.kas.schedule-backup.index', compact('schedulesByProject'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $today = now();
        $employee = Employee::all();
        $project = Project::all();
        $project2 = Project::all();
        $shift = Shift::all();
        $schedule = Schedule::all();

        $current_month = $today->format('F');
        $current_year = $today->format('Y');

        return view('pages.hc.kas.schedule-backup.create', compact('employee','project','project2','shift','schedule','current_month','current_year'));
    }

    public function getEmployeesWithDayOff(Request $request)
    {
        $tanggal = $request->input('tanggal');
        $employeesWithDayOff = Schedule::where('tanggal', $tanggal)
                                ->where('shift', 'Off')
                                ->pluck('employee');

        $employees = Employee::whereIn('nik', $employeesWithDayOff)->get();

        return response()->json(['employees' => $employees]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
    // Validasi input
        $validatedData = $request->validate([
            'tanggal.*' => 'required|date',
            'employee.*' => 'required|numeric', 
            'periode.*' => 'required|string',
            'project.*' => 'required|numeric',
            'shift.*' => 'required|string',
        ]);

        try {
            // Loop melalui setiap item untuk menyimpan data
            foreach ($request->input('tanggal') as $key => $tanggal) {
                $employee = $request->input('employee')[$key];
                $periode = $request->input('periode')[$key];
                $project = $request->input('project')[$key];
                $shift = $request->input('shift')[$key];

                // Lakukan penyimpanan data di sini
                $schedule = new ScheduleBackup();
                $schedule->tanggal = $tanggal;
                $schedule->employee = $employee;
                $schedule->periode = $periode;
                $schedule->project = $project;
                $schedule->shift = $shift;
                $schedule->save();
            }

            // Jika berhasil, kembalikan dengan pesan sukses
            return redirect()->route('backup-schedule.index')->with('success', 'Data backup schedule berhasil disimpan.');
        } catch (\Exception $e) {
            // Jika terjadi kesalahan, kembalikan dengan pesan kesalahan
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan data.' . $e);
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
