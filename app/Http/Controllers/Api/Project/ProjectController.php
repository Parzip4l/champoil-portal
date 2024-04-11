<?php

namespace App\Http\Controllers\Api\Project;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\ModelCG\Project;
use App\ModelCG\Schedule;


class ProjectController extends Controller
{
    public function project_schedule(){
        $records = Project::all();
        if(!empty($records)){
            foreach($records as $row){
                $row->cek_schedule = Schedule::where('project',$row->id)
                                      ->whereRaw("periode LIKE '%" . date('F-Y',strtotime('+1 month')) . "%'")
                                      ->count();
            }
        }
        
        return response()->json($records);
    }
}
