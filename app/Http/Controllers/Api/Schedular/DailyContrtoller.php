<?php

namespace App\Http\Controllers\Api\Schedular;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\ModelCG\Project;
use App\ModelCG\Schedule;
use App\ModelCG\Absen;
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
            'data' => $result
        ]);
        
    }
}
