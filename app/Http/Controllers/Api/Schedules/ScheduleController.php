<?php

namespace App\Http\Controllers\Api\Schedules;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Employee;
use App\ModelCG\Project;
use App\ModelCG\ProjectRelations;
use App\ModelCG\Schedule;
use App\ModelCG\Shift;
use App\ModelCG\ScheduleBackup;
use App\Absen;
use Carbon\Carbon;
use App\Absen\RequestAbsen;


class ScheduleController extends Controller
{
    public function data_shift($id){
        $shift = ProjectRelations::join('shifts','shifts.id','=','project_relations.id_shift')
                                 ->where('id_prject',$id)
                                 ->get();
        if($shift){
            return response()->json($shift, 200);
        }else{
            return response()->json("shift project not found", 201);
        }
    }

    public function index(Request $request)
    {
        //
        $start = date('Y-m-d');
        $end = date('Y-m-d');
        if(!empty($request->input('periode'))){
            $periode = $request->input('periode');
            // Tambahkan satu bulan ke periode
            $periode_min_1_month = date('m', strtotime("-1 month", strtotime($periode)));

            // Ambil bulan dari periode yang sudah ditambahkan satu bulan
            $bulan = date('m', strtotime($periode));

            $start = date('Y').'-'.$periode_min_1_month.'-'.'21';
            $end = date('Y').'-'.$bulan.'-'.'20';

            
        }

        if(!empty($request->input('tanggal'))){
            $tanggal = $request->input('tanggal');
            $explode = explode(' to ',$tanggal);
            $start=$explode[0];
            $end=$explode[1];
           
        }

        $project = Project::where('deleted_at',NULL)->where('company','Kas')->get();
        if($project){
            foreach($project as $row){
                $row->persentase_backup=0;
                $row->persentase_absen=0;
                $row->persentase_tanpa_clockout = 0;
                  
                $schedule = Schedule::where('project',$row->id)
                                           ->whereBetween('tanggal', [$start, $end])
                                           ->where('shift','!=','OFF');
                $row->schedule = $schedule->count();
                $row->absen  =0;
                foreach($schedule->get() as $sch){
                    $absen = Absen::where('project', $sch->project)
                                    ->wheere('tanggal', $sch->tanggal)
                                    ->wheere('nik', $sch->employee)
                                    ->count();
                    if($absen >  0){
                        $row->absen +=1;
                    }
                }

                

                $row->schedule_backup = ScheduleBackup::where('project',$row->id)->whereBetween('tanggal', [$start, $end])->count();
              

                
                
                $row->tanpa_clockout = Absen::where('project', $row->id)
                                    ->whereBetween('tanggal', [$start, $end])
                                    ->whereNotNull('clock_in')
                                    ->whereNull('clock_out')
                                    ->count();
                                    
                $row->need_approval = Schedule::join('requests_attendence','requests_attendence.employee','=','schedules.employee')
                                                    ->where('schedules.project',$row->id)
                                                    ->whereBetween('schedules.tanggal', [$start, $end])
                                                    ->whereBetween('requests_attendence.tanggal', [$start, $end])
                                                    ->where('requests_attendence.aprrove_status','Pending')
                                                    ->count();
                $row->approved = Schedule::join('requests_attendence','requests_attendence.employee','=','schedules.employee')
                                            ->where('schedules.project',$row->id)
                                            ->whereBetween('schedules.tanggal', [$start, $end])
                                            ->whereBetween('requests_attendence.tanggal', [$start, $end])
                                            ->where('requests_attendence.aprrove_status','Approved')
                                            ->count();

                // Persentase Tanpa Clockout
                if ($row->absen > 0) {
                    $row->persentase_tanpa_clockout = round(($row->tanpa_clockout / $row->absen) * 100, 2);
                } else {
                    $row->persentase_tanpa_clockout = 0;
                }
            
                if($row->absen > 0 && $row->schedule > 0){
                    $row->persentase_absen = round(($row->absen / $row->schedule) * 100,2);
                }
                
                $row->absen_backup = Absen::where('project_backup',$row->id)
                                            ->whereBetween('tanggal', [$start, $end])
                                            ->count();    
                if($row->absen_backup > 0){
                    $row->persentase_backup = round(($row->absen_backup / $row->schedule_backup) * 100,2);
                }
            }
        }

        $data['project']=$project;
        if($data){
            return response()->json($data, 200);
        }else{
            return response()->json("shift project not found", 201);
        }
    }
}
