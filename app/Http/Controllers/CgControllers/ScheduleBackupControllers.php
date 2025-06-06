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
        $schedulesByProject = ScheduleBackup::orderBy('id','DESC')->get();
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
        if ($today->day >= 21) {
            $next_month = $today->copy()->addMonth();
            $current_month = $next_month->format('F'); // Update nama bulan
            $current_year = $next_month->format('Y'); // Update tahun jika berubah
        }

        return view('pages.hc.kas.schedule-backup.create', compact('employee','project','project2','shift','schedule','current_month','current_year'));
    }

    public function getEmployeesWithDayOff(Request $request)
    {
        try {
            $tanggal = $request->input('tanggal');
            $originalShift  = $request->input('shift');

            $employeesWithShift = Schedule::where('tanggal', $tanggal)
                ->where('shift','!=', $originalShift)
                ->pluck('employee');

            // Ambil data karyawan yang memiliki shift dan tanggal yang sesuai dari tabel Employee
            $employees = Employee::whereIn('nik', $employeesWithShift)
                ->where('unit_bisnis', 'Kas')
                ->get();

            $bko = Employee::where('unit_bisnis', 'Kas')->whereIN('organisasi',['FRONTLINE OFFICER','Frontline Officer'])->get();

            $data_bko=[];
            foreach($bko as $row){
                $check = Schedule::where('employee',$row->nik)->where('tanggal',$tanggal)->count();
                if($check==0){
                    $data_bko[]=$row;
                }
            }

            $mergedEmployees = $employees->merge($data_bko);
    
            return response()->json(['employees' => $mergedEmployees]);
        } catch (\Exception $e) {
            // Log the exception
            \Log::error($e);
    
            // Return an error response
            return response()->json(['error' => 'Internal Server Error'. $e], 500);
        }
        
    }

    public function getManPower(Request $request)
    {
        $project = $request->input('project');
        $employeeReplace = Schedule::where('project', $project)
                                ->pluck('employee');

        $employeesData = Employee::whereIn('nik', $employeeReplace)
                                ->where('unit_bisnis', 'Kas')
                                ->get();

        return response()->json(['EmployeeReplace' => $employeesData]);
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
                $manpowerreplace = $request->input('manpower')[$key];


                if ($shift == 'NS-P') {
                    $ShiftDataChange = 'BCK-P';
                } else if ($shift == 'NS-M'){
                    $ShiftDataChange = 'BCK-M';
                } else {
                    $ShiftDataChange = 'BCK-ML';
                }
                
                // Lakukan penyimpanan data di sini
                $schedule = new ScheduleBackup();
                $schedule->tanggal = $tanggal;
                $schedule->employee = $employee;
                $schedule->man_backup = $manpowerreplace;
                $schedule->periode = $periode;
                $schedule->project = $project;
                $schedule->shift = $ShiftDataChange;
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
    // get manpower
    
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
        $backup = ScheduleBackup::find($id);
        $backup->delete();
        return redirect()->route('backup-schedule.index')->with('success', 'Backup Successfully Deleted');
    }
}
