<?php

namespace App\Http\Controllers\Api\Task;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

// Load Model
use App\ModelCG\TaskGlobal; 

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
}
