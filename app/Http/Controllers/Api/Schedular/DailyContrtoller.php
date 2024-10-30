<?php

namespace App\Http\Controllers\Api\Schedular;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\ModelCG\Project;
use App\ModelCG\Schedule;
use App\ModelCG\Absen;
use App\Employee;
use Carbon\Carbon;

class DailyContrtoller extends Controller
{
    public function daily_absen(){
        $records = Project::whereNull('deleted_at')->where('company','Kas')->get();
        $yesterday = Carbon::yesterday()->format('Y-m-d');
        $result = [];
        
        if (!$records->isEmpty()) {
            foreach ($records as $row) {
                // Fetch all schedules for this project on the given day where shift is not 'OFF'
                $schedules = Schedule::where('schedules.project', $row->id)
                    ->join('karyawan','karyawan.nik','=','schedules.employee')
                    ->where('shift', '!=', 'OFF')
                    ->where('schedules.tanggal', $yesterday)
                    ->get();
                
                // Total number of schedules
                $schedules_total = $schedules->count();
        
                // Initialize counters
                $absen = 0;
                $not_absen = 0;
                $no_absen =[];

        
                // Count absentees and presentees based on clock_in field
                foreach ($schedules as $rs) {
                    // Check attendance for the employee on the given date
                    $jml_absen = DB::table('absens')
                        ->where('nik', $rs->employee)
                        ->where('tanggal', $yesterday)
                        ->count();

    

                    if ($jml_absen > 0) {
                        $absen += 1;
                        
                    } else {
                        $not_absen += 1;
                        $no_absen[]=$rs->nama;
                    }
                }
        
                // Add result for each project
                $result[] = [
                    "project_name" => $row->name,
                    "leader_pic"=>karyawan_bynik($row->leader_pic),
                    "schedule_on" => $schedules_total,  
                    "absen" => $absen,                 
                    "not_absen" => $not_absen,
                    "no_absen"=>$no_absen           
                ];
            }
        }
        
        // Return the records as a JSON response
        return response()->json([
            'status' => 'success',
            'tanggal'=>$yesterday,
            'data' => $result
        ]);
        
    }

    
    public function seven_day() {
        // Fetch active employees in the specific business unit
        $employees = Employee::where('unit_bisnis', 'like', '%Kas%')
            ->where('resign_status', 0)
            ->get();
    
        $result = [];
    
        foreach ($employees as $employee) {
            // Retrieve schedules for the employee within the specified date range and non-OFF shifts
            $schedules = Schedule::where('employee', $employee->nik)
                ->where('shift', '!=', 'OFF')
                ->whereBetween('tanggal', ['2024-10-15', '2024-10-30'])
                ->orderBy('tanggal', 'desc') // Order by date instead of ID for relevance
                ->get();
    
            $schedule_data = [];
    
            foreach ($schedules as $schedule) {
                // Get absence count for each schedule date
                $absen_count = Absen::where('nik', $employee->nik)
                    ->where('tanggal', $schedule->tanggal)
                    ->count();
    
                $schedule_data[] = [
                    "tanggal" => $schedule->tanggal,
                    "shift" => $schedule->shift,
                    "absen_count" => $absen_count
                ];
            }
    
            $result[] = [
                'employee' => $employee->nik,
                'nama' => $employee->nama,
                'schedules' => $schedule_data
            ];
        }
    
        return response()->json([
            'status' => 'success',
            'data' => $result
        ]);
    }
    
    
}
