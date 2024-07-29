<?php

namespace App\Http\Controllers\Api\Task;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

// Load Model
use App\ModelCG\TaskGlobal; 
use App\ModelCG\Project;
use App\ModelCG\Patroli; 
use App\ModelCG\Task; 
use App\ModelCG\List_task;

class TaskController extends Controller
{
    public function task($id_project){
        $error=true;
        $msg="Task Is Empty";
        $result=[];

        $task = TaskGlobal::whereIn('project',[$id_project,0])->get();
        if($task){
            $result=$task;
            $error=false;
            $msg="Task Record";
        }

        $records=[
            "records"=>$result,
            "error"=>$error,
            "message"=>$msg
        ];

        return response()->json($records);
    }

    public function project(){
        $records = Project::all();
        $records=[
            "records"=>$records,
            "error"=>false,
            "message"=>"Data"
        ];

        return response()->json($records);
    }

    public function report_patroli(Request $request){
        $records = Project::all();
        $report = Task::where('project_id',$request->input('project_id'))->get();
        $data=[];
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;
        $daysInMonth = Carbon::now()->daysInMonth;

        $dates = [];

        for ($day = 1; $day <= $daysInMonth; $day++) {
            $dates[] = Carbon::create($currentYear, $currentMonth, $day)->toDateString();
        }

        if(!empty($dates)){
            foreach($dates as $key=>$val){
                $specifiedDate = $val;
                $tanggal=date('d',strtotime($val));
                $count = Patroli::join('master_tasks','master_tasks.id','=','patrolis.id_task')
                                ->where('master_tasks.project_id',$request->input('project_id'))
                                ->where(DB::raw('DATE_FORMAT(patrolis.created_at, "%Y-%m-%d")'),'=',$specifiedDate)
                                ->count();  
                if($count==0){
                    $label="Patrol Empty";
                }else{
                    $label="Check Detail";
                }
                $button=$label;

                $data[]=[
                    "tanggal"=>$tanggal,
                    "jumlah"=>$count,
                    "label"=>$label
                ];
            }
        }

        $records=[
            "records"=>$data,
            "project_id"=>$request->input('project_id'),
            "error"=>false,
            "message"=>"Data"
        ];

        return response()->json($records);
    }

    public function report_patroli_detail($id,$tanggal){
        $result=[];
        $report = Task::where('id',$id)->first();
        if(!empty($report)){
            $list = List_task::where('id_master',$id)->get();
            foreach($list as $det){
                $daily = Patroli::where('id_task',$det->id)
                                        ->whereYear('created_at', date('Y',strtotime($tanggal)))
                                        ->whereMonth('created_at', date('m',strtotime($tanggal)))
                                        ->whereDay('created_at',  date('d',strtotime($tanggal)))
                                        ->get();
                if($daily){
                    foreach($daily as $day){
                        $result[]=[
                            "petugas"=>karyawan_bynik($day->employee_code)->nama,
                            "tanggal"=>date('d F Y H:i:s',strtotime($day->created_at)),
                            "kondisi"=>$day->status,
                            "deskripsi"=>$day->description,
                            "point_name"=>$det->task,
                            "photo"=>url($day->image)
                        ];
                    }
                }
            }
        }
        
        $data=[
            "tanggal"=>$tanggal,
            "report"=>$result
        ];
    
        return response()->json($data);
    }
}
