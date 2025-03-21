<?php

namespace App\Http\Controllers\Api\AllData;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Absen;
use App\Employee;
use App\ModelCG\Schedule;
use App\ModelCG\ScheduleBackup;
use App\ModelCG\Project;
use Illuminate\Support\Facades\DB;

class AttendanceController extends Controller
{
    public function index(Request $request){

        $project = $request->project_id ?? "All";
        // Validate request
        if($request->start == null && $request->end == null){
            return response()->json([
                'status' => 'success',
                'data' => []
            ]);
        }else{

            $data = Schedule::select('employee','project', DB::raw('COUNT(*) as total_schedules'))
                ->whereBetween('tanggal', [$request->start, $request->end]);

            if($project != "ALL") {
                $data->where('project', $project);
            }

            $data = $data->groupBy('employee','project')
                ->get();

            $start_date = $request->start;
            $end_date = $request->end;

            $result = []; // Initialize an array to store the final data

            foreach ($data as $row) {
                $employee = karyawan_bynik($row->employee);

                if (!$employee) {
                    continue; // Skip if employee data is not found
                }

                $employeeData = [
                    'employee' => $row->employee,
                    'employee_name' => $employee->nama,
                    'project_name'=>project_byID($row->project)->name,
                    'total_schedules' => $row->total_schedules,
                    'schedules' => [] // Initialize schedules as an array
                ];

                $schedules = Schedule::where('employee', $row->employee)
                    ->whereBetween('tanggal', [$start_date, $end_date])
                    ->get();

                foreach ($schedules as $schedule) {
                    $absen = Absen::where('nik', $row->employee)
                        ->where('tanggal', $schedule->tanggal)
                        ->first();

                    $employeeData['schedules'][$schedule->tanggal] = [
                        'shift' => $schedule->shift,
                        'project' => $absen->project->name ?? '-',
                        'clock_in' => $absen->clock_in ?? '-',
                        'clock_out' => $absen->clock_out ?? '-',
                        'status' => $absen->status ?? '-'
                    ];
                }

                $result[] = $employeeData; // Add the employee data to the result array
            }

            if (empty($result)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Data not found'
                ], 404);
            }

            return response()->json([
                'status' => 'success',
                'data' => $result
            ]);
        }
            
    }

}
