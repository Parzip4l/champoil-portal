<?php

namespace App\Http\Controllers\PengajuanSchedule;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\PengajuanSchedule\PengajuanSchedule;
use App\Employee;
use App\ModelCG\Schedule;
use App\ModelCG\Shift;
use App\ModelCG\Project;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PengajuanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $permissions = explode(',', Auth::user()->permission);
        $employeeCode = Auth::user()->employee_code;

        if (in_array('superadmin_access', $permissions)) {
            $datapengajuan = PengajuanSchedule::groupBy('project')->get();
        } else {
            $datapengajuan = PengajuanSchedule::with('project')
                ->select('id', 'project', 'periode', 'status', DB::raw('count(*) as schedule_count'))
                ->groupBy('id', 'project', 'periode', 'status')
                ->get();
        }


        return view('pages.pengajuanschedule.index', compact('datapengajuan'));
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
        $employee = Employee::where('unit_bisnis', $unit_bisnis)->get();
        $project = Project::all();

        $current_month = $today->format('F');
        $current_year = $today->format('Y'); 

        return view('pages.pengajuanschedule.create', [
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
        $employeeCode = Auth::user()->employee_code;
        $request->validate([
            'employee' => 'required',
            'project' => 'required',
            'tanggal.*' => 'required|date',
            'shift.*' => 'required|string|max:255',
            'periode' => 'required',
        ]);

        // Ambil data dari formulir
        $employees = $request->input('employee');
        $project = $request->input('project');
        $tanggal = $request->input('tanggal');
        $shifts = $request->input('shift');
        $periodes = $request->input('periode');
        
        // Generate a random code for the schedule
        $randomCode = $this->generateRandomCode();

        // Prepare an array to store in JSON format
        $scheduleTanggal = [];
        $scheduleShift = [];

        // Populate the array with data
        for ($i = 0; $i < count($tanggal); $i++) {
            $scheduleTanggal[] = [
                'tanggal' => $tanggal[$i],
            ];
        }

        for ($i = 0; $i < count($tanggal); $i++) {
            $scheduleShift[] = [
                'shift' => $shifts[$i],
            ];
        }

        // Convert the array to JSON
        $jsonScheduleData = json_encode($scheduleTanggal);
        $jsonshiftData = json_encode($scheduleShift);

        // Save the JSON data in the database
        $schedule = new PengajuanSchedule();
        $schedule->fill([
            'periode' => $periodes,
            'employee' => $employees,
            'schedule_code' => $randomCode,
            'project' => $project,
            'tanggal' => $jsonScheduleData,
            'shift' => $jsonshiftData,
            'namapengaju' => $employeeCode,
            'status' => 'Ditinjau',
        ]);
        $schedule->save();
       

        return redirect()->route('pengajuan-schedule.index')->with('success', 'Pengajuan Schedules Berhasil Diajukan.');
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
        $schedules = PengajuanSchedule::select('employee', 'periode', DB::raw('MIN(id) as schedule_id'))
            ->where('project', $project)
            ->where('periode', $periode)
            ->groupBy('employee', 'periode');

        $schedulesWithDetails = PengajuanSchedule::whereIn('id', $schedules->pluck('schedule_id'))
            ->get();

        foreach ($schedulesWithDetails as $dataProject)
        {
            $picProject = $dataProject->namapengaju;
            $karyawan = $dataProject->employee;
        }

        $schedulekaryawandetails = PengajuanSchedule::where('project', $project)
                                ->where('periode', $periode)
                                ->where('employee', $karyawan)
                                ->get();

        $totals = [
            'totalShift' => 0,
            'totalLibur' => 0,
            'totalShiftMalam' => 0,
            'totalShiftPagi' => 0,
            'totalShiftMiddle' => 0,
        ];
        $totalShift = 0;
        
        foreach ($schedulekaryawandetails as $dataKaryawan) {
            $scheduleData = json_decode($dataKaryawan->shift);
            foreach ($scheduleData as $shift) {
                $shiftValue = strtoupper($shift->shift);
                if ($shiftValue != 'OFF') {
                    $totalShift++;
                }

                switch ($shiftValue) {
                    case 'OFF':
                        $totals['totalLibur']++;
                        break;
                    case 'NS-P':
                        $totals['totalShiftPagi']++;
                        break;
                    case 'NS-M':
                        $totals['totalShiftMiddle']++;
                        break;
                    case 'NS-ML':
                        $totals['totalShiftMalam']++;
                        break;
                    default:
                        $totals['totalShift']++;
                }
            }
        }
        // Dapatkan hasil total
        $totalLibur = $totals['totalLibur'];
        $totalShiftMalam = $totals['totalShiftMalam'];
        $totalShiftPagi = $totals['totalShiftPagi'];
        $totalShiftMiddle = $totals['totalShiftMiddle'];
        return view('pages.pengajuanschedule.detailproject', [
            'project' => $project,
            'periode' => $periode,
            'namapengaju' => $picProject,
            'schedules' => $schedulesWithDetails,
            'schedulekaryawan' => $schedulekaryawandetails,
            'malam' => $totalShiftMalam,
            'pagi' => $totalShiftPagi,
            'middle' => $totalShiftMiddle,
            'libur' => $totalLibur,
            'totalshift' => $totalShift,
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

    public function updateStatusSetuju($project, $periode)
    {
        $userId = Auth::id();
        $EmployeeCode = Auth::user()->employee_code;
        
        try {
            $requestSchedule = PengajuanSchedule::where('project', $project)->where('periode', $periode)->firstOrFail();
            
            if ($requestSchedule->status != 'Approved') {
                $requestSchedule->status = 'Approved';
                $requestSchedule->disetujui_oleh = $EmployeeCode;
                $requestSchedule->save();
        
                $tanggalArray = json_decode($requestSchedule->tanggal, true);
                $shiftArray = json_decode($requestSchedule->shift, true);
        
                if (!is_array($tanggalArray) || !is_array($shiftArray)) {
                    throw new \Exception('Invalid JSON format in database for tanggal or shift.');
                }
        
                for ($i = 0; $i < count($tanggalArray); $i++) {
                    $schedule = new Schedule();
                    $schedule->schedule_code = $requestSchedule->schedule_code;
                    $schedule->employee = $requestSchedule->employee;
                    $schedule->project = $requestSchedule->project;
                    $schedule->tanggal = $tanggalArray[$i]['tanggal'];
                    $schedule->shift = $shiftArray[$i]['shift'];
                    $schedule->periode = $requestSchedule->periode;
                    $schedule->save();
                }
            }
        
            return redirect()->back()->with('success', 'Data Pengajuan Berhasil Diupdate.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error updating data: ' . $e->getMessage());
        }
        
    }

    public function RejectSchedule($project, $periode)
    {
        $userId = Auth::id();
        $EmployeeCode = Auth::user()->employee_code;
        
        try {
            $requestSchedule = PengajuanSchedule::where('project', $project)->where('periode', $periode)->firstOrFail();
            
            if ($requestSchedule->status != 'Approved') {
                $requestSchedule->status = 'Rejected';
                $requestSchedule->disetujui_oleh = $EmployeeCode;
                $requestSchedule->save();
            }
        
            return redirect()->back()->with('success', 'Data Pengajuan Berhasil Diupdate.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error updating data: ' . $e->getMessage());
        }
        
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
        try {
            $pengajuan = PengajuanSchedule::find($id);
            $pengajuan->delete();

            return redirect()->back()->with('success', 'Pengajuan Berhasil Dibatalkan');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }
}
