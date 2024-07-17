<?php

namespace App\Http\Controllers\Api\Schedules;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Employee;
use App\ModelCG\Project;
use App\ModelCG\ProjectRelations;
use App\ModelCG\Schedule;
use App\ModelCG\Shift;

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
}
