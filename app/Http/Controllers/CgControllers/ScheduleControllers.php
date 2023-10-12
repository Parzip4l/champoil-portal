<?php

namespace App\Http\Controllers\CgControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\ModelCG\Schedule;
use App\ModelCG\Shift;
use App\Employee;
use App\ModelCG\Project;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ScheduleControllers extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $schedulesByProject = Schedule::with('project')
        ->select('project', 'periode', DB::raw('count(*) as schedule_count'))
            ->groupBy('project', 'periode')
            ->get();

        return view('pages.hc.kas.schedule.index', compact('schedulesByProject'));
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

        $shift = Shift::all();
        $employee = Employee::all();
        $project = Project::all();

        $current_month = $today->format('F');
        $current_year = $today->format('Y'); 

        return view('pages.hc.kas.schedule.create', [
            'dates_for_form' => $dates_for_form,
            'employee' => $employee,
            'shift' => $shift,
            'dates_for_form2' => $dates_for_form,
            'project' => $project,
            'current_month' => $current_month,
            'current_year' => $current_year,
        ]);
    }

    function generateRandomCode($length = 6) {
        $characters = '0123456789';
        $code = '';

        for ($i = 0; $i < $length; $i++) {
            $code .= $characters[rand(0, strlen($characters) - 1)];
        }

        return $code;
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
            'employee' => 'required',  
            'project' => 'required',
            'tanggal.*' => 'required|date',
            'shift.*' => 'required|string|max:255',
        ]);

        // Ambil data dari formulir
        $employees = $request->input('employee');
        $project = $request->input('project');
        $tanggal = $request->input('tanggal');
        $shifts = $request->input('shift');
        $periodes = $request->input('periode');

        $randomCode = $this->generateRandomCode();

        for ($i = 0; $i < count($tanggal); $i++) {
            $schedule = new Schedule();
            $schedule->schedule_code = $randomCode;
            $schedule->employee = $employees;
            $schedule->project = $project;
            $schedule->tanggal = $tanggal[$i];
            $schedule->shift = $shifts[$i];
            $schedule->periode = $periodes;
            $schedule->save();
        }

        return redirect()->route('schedule.index')->with('success', 'Schedules created successfully.');
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

    public function showDetails($project, $periode)
    {
        $schedules = Schedule::select('employee', 'periode', DB::raw('MIN(id) as schedule_id'))
        ->where('project', $project)
        ->where('periode', $periode)
        ->groupBy('employee', 'periode');

        $schedulesWithDetails = Schedule::whereIn('id', $schedules->pluck('schedule_id'))
            ->get();

        return view('pages.hc.kas.schedule.detailprojectschedule', [
            'project' => $project,
            'periode' => $periode,
            'schedules' => $schedulesWithDetails,
        ]);
    }

    public function showDetailsEmployee($project, $periode, $employee)
    {
        $schedulesWithDetails = Schedule::where('project', $project)
        ->where('periode', $periode)
        ->where('employee', $employee)
        ->get();

        $employeeName = Employee::where('nik', $employee)->value('nama');
        $shift = Shift::all();

        return view('pages.hc.kas.schedule.detailsscheduleemployee', [
            'employeeName' => $employeeName,
            'schedules' => $schedulesWithDetails,
            'shift' => $shift,
        ]);

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
