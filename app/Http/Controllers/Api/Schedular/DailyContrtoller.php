<?php

namespace App\Http\Controllers\Api\Schedular;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\ModelCG\Project;
use App\ModelCG\Schedule;
use App\ModelCG\ScheuleParent;
use App\ModelCG\Absen;
use App\Employee;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SevenDayExport;

class DailyContrtoller extends Controller
{
    public function daily_absen(Request $request){
        $records = Project::whereNull('deleted_at')->where('company',$request->input('unit_bisnis'))->get();
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

                $cout_schedule =0;
                if(!empty($schedules)){
                    foreach($schedules as $sh){
                        $sh->cek = ScheuleParent::where('employee_code',$sh->employee)->where('periode',$sh->periode)->where('project_id',$sh->project)->count();
                        if($sh->cek == 0){
                            $cout_schedule +=1;
                        }
                    }
                }
                
                // Total number of schedules
                $schedules_total = $cout_schedule;
        
                // Initialize counters
                $absen = 0;
                $not_absen = 0;
                $no_absen =[];

        
                // Count absentees and presentees based on clock_in field
                foreach ($schedules as $rs) {
                    // Check attendance for the employee on the given date
                    if($rs->cek==0){
                        $jml_absen = DB::table('absens')
                            ->where('nik', $rs->employee)
                            ->where('tanggal', $yesterday)
                            ->count();

        

                        if ($jml_absen > 0) {
                            $absen += 1;
                            
                        } else {
                            $not_absen += 1;
                            $no_absen[]=["nama"=>$rs->nama,"slack_id"=>$rs->slack_id];
                        }
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

    public function report_absen() {
        $records = Project::whereNull('deleted_at')
            ->where('company', 'Kas')
            ->get();
    
        $date1 = "2024-12-21";
        $yesterday = Carbon::yesterday()->format('Y-m-d');
        
        $result = [];
    
        if (!$records->isEmpty()) {
            foreach ($records as $row) {
                // Clone the base schedule query to ensure a fresh query for each project
                $projectSchedules = Schedule::select('karyawan.nama', 'schedules.*')
                    ->join('karyawan', 'karyawan.nik', '=', 'schedules.employee')
                    ->where('schedules.shift', '!=', 'OFF')
                    ->where('project', $row->id)
                    ->whereBetween('tanggal', [$date1, $yesterday])
                    ->get();
    
                if ($projectSchedules->isNotEmpty()) {
                    foreach ($projectSchedules as $absen) {
                        // Count the absences for the employee on the specified date
                        $absen->jml = DB::table('absens')
                            ->where('nik', $absen->employee)
                            ->where('tanggal', $absen->tanggal)
                            ->count();
                    }
                }
    
                // Add the project data and related schedules to the result array
                $result[] = [
                    'project' => $row->name,
                    'schedule' => $projectSchedules,
                ];
            }
        }
    
        // Return the records as a JSON response
        return response()->json([
            'status' => 'success',
            'tanggal' => $date1 . ' - ' . $yesterday,
            'data' => $result
        ]);
    }
    
    public function seven_day() {
        // Fetch active employees in the specific business unit
        $employees = Employee::where('unit_bisnis', 'like', '%Kas%')
            ->where('resign_status', 0)
            ->whereIn('organisasi',["FRONTLINE OFFICER","Frontline Officer"])
            ->get();
    
        $result = [];
    
        foreach ($employees as $employee) {
            // Retrieve schedules for the employee within the specified date range and non-OFF shifts
            $schedules = Schedule::where('employee', $employee->nik)
                ->whereBetween('tanggal', ['2024-10-20', '2024-10-29'])
                ->whereIN('periode',["OCTOBER-2024","NOVEMBER-2024"])
                ->orderBy('tanggal', 'desc') // Order by date instead of ID for relevance
                ->get();
    
            $schedule_data = [];
            $off=0;
            $jml_absen =0;
            foreach ($schedules as $schedule) {
                
                if($schedule->shift != 'OFF'){
                    $off +=1;
                }

                // Get absence count for each schedule date
                $absen_count = DB::table('absens')->where('nik', $schedule->employee)
                    ->where('tanggal', $schedule->tanggal)
                    ->count();
                if($absen_count != 0){
                    $jml_absen +=1;
                }
                $schedule_data[] = [
                    "tanggal" => $schedule->tanggal,
                    "shift" => $schedule->shift,
                    "absen_count" => $absen_count
                ];
            }
    
            $result[] = [
                'employee' => $employee->nik,
                'nama' => $employee->nama,
                "jml_schedule"=>$off,
                "jml_absen"=>$jml_absen,
                'schedules' => $schedule_data
            ];
        }
    
        return response()->json($result);
    }

    public function reminder_schedule($key,$periode){
        $project = Project::whereNull('deleted_at')->where('company', 'like', '%' . $key . '%')->get();
        $schedule_onperiode=[];
        $not_scheudle=[];
        if($project){
            foreach($project as $record){
                $count_schedule = Schedule::where('project',$record->id)->where('periode','like','%'.$periode.'%')->count();
                if($count_schedule  > 0){
                    $schedule_onperiode[]=$record;
                }else{
                    $not_scheudle[]=$record;
                }
            }
        }

        return response()->json([
            'status' => 'success',
            'not_scheudle' => $not_scheudle,
            'schedule_onperiode' => $schedule_onperiode
        ]);
    }
    
    
}
