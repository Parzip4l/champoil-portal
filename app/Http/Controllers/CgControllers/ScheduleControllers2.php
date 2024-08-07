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
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ScheduleImport;
use App\Exports\ScheduleExport;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ScheduleControllers extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $currentYear = date('Y');
        $selectedPeriod = $request->input('periode', null);

        $get_data = Schedule::with('project')
            ->select('project', 'periode', DB::raw('count(*) as schedule_count'))
            ->groupBy('project', 'periode')
            ->orderBy(DB::raw("DATE_FORMAT(STR_TO_DATE(periode, '%Y-%m'), '%M-%Y')"), 'ASC'); // Ordering by month in the format MMM-YYYY

        if (Auth::user()->project_id == NULL) {
            if ($selectedPeriod) {
                $get_data->where('periode', $selectedPeriod);
            }
            $schedulesByProject = $get_data->get();
        } else {
            $get_data = $get_data->where('project', Auth::user()->project_id);
            if ($selectedPeriod) {
                $get_data->where('periode', $selectedPeriod);
            }
            $schedulesByProject = $get_data->get();
        }

        return view('pages.hc.kas.schedule.index', compact('schedulesByProject', 'currentYear', 'selectedPeriod'));
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
        $employeeCode = auth()->user()->employee_code;
        $employee = Employee::where('nik', $employeeCode)->first();
        $unit_bisnis = $employee->unit_bisnis;
        $employee = Employee::where('unit_bisnis', $unit_bisnis)->where('organisasi','Frontline Officer')->get();
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

    // Schedule Import
    public function importSchedule(Request $request)
    {
        try {
            $request->validate([
                'csv_file' => 'required|mimes:xlsx,csv,txt',
            ]);
            $data = $request->file('csv_file');

            $namaFIle = $data->getClientOriginalName();
            $data->move('ScheduleImport', $namaFIle);
            Excel::import(new ScheduleImport, \public_path('/ScheduleImport/'.$namaFIle));

            return redirect()->route('schedule.index')->with('success', 'Import berhasil!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Import gagal. ' . $e->getMessage());
        }
    }

    public function exportSchedule() 
    {
        return Excel::download(new ScheduleExport, 'schedule.xlsx');
        return redirect()->back()->with('success', 'Download Berhasil !');
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

        $schedule_code = Schedule::where('project', $project)
                                ->where('periode', $periode)
                                ->where('employee', $employee)
                                ->pluck('schedule_code')
                                ->first();

        $employeeName = Employee::where('nik', $employee)->value('nama');
        $shift = Shift::all();
        return view('pages.hc.kas.schedule.detailsscheduleemployee', [
            'employeeName' => $employeeName,
            'schedules' => $schedulesWithDetails,
            'shift' => $shift,
            'schedule_code' => $schedule_code,
        ]);
    }

    // Edit Manual Schedule

    public function updateMultiple(Request $request)
    {
        // Validasi jika diperlukan
        $request->validate([
            'schedules.*.tanggal' => 'required|date',
            'schedules.*.shift' => 'required',
            // Tambahkan aturan validasi sesuai kebutuhan
        ]);

        // Loop melalui data yang dikirimkan melalui formulir
        foreach ($request->schedules as $scheduleId => $data) {
            // Temukan entitas jadwal berdasarkan ID
            $schedule = Schedule::findOrFail($scheduleId);

            // Update atribut jadwal dengan data yang diterima dari formulir
            $schedule->tanggal = $data['tanggal'];
            $schedule->shift = $data['shift'];
            // Lanjutkan dengan atribut lain yang perlu diperbarui

            // Simpan perubahan
            $schedule->save();
        }

        // Redirect ke halaman terkait atau tampilkan pesan sukses
        return redirect()->back()->with('success', 'Jadwal berhasil diperbarui');
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
        $request->validate([
            // Atur aturan validasi sesuai kebutuhan Anda
        ]);

        // Temukan entitas jadwal berdasarkan ID
        $schedule = Schedule::findOrFail($id);

        // Update atribut jadwal dengan data yang diterima dari formulir
        $schedule->tanggal = $request->tanggal;
        $schedule->shift = $request->shift;
        // Lanjutkan dengan atribut lain yang perlu diperbarui

        // Simpan perubahan
        $schedule->save();

        $project = $schedule->project; 
        $periode = $schedule->periode; 
        $employee = $schedule->employee; 

        // Redirect ke halaman terkait atau tampilkan pesan sukses
        return redirect()->route('schedule.employee', [
            'project' => $project,
            'periode' => $periode,
            'employee' => $employee,
        ])->with('success', 'Jadwal berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($schedule_code)
    {
        $affectedRows = Schedule::where('schedule_code', $schedule_code)->delete();

        if ($affectedRows > 0) {
            return redirect()->route('schedule.index')->with('success', 'Schedule Successfully Deleted');
        } else {
            return redirect()->route('schedule.index')->with('error', 'Schedule Not Found');
        }
    }
}
