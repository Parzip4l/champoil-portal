<?php

namespace App\Http\Controllers\Api\Project;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\ModelCG\Project;
use App\ModelCG\ProjectDetails;
use App\ModelCG\Schedule;
use App\ModelCG\Datamaster\ProjectShift;


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

    public function projectList(Request $request){
        $records = Project::where('company', 'like', '%' . $request->company . '%')
                            ->whereNull('deleted_at') // Ensures only non-deleted records are retrieved
                            ->get();

        if($record->isEmpty()){
            return response()->json(['status' => 'success','message' => 'No projects found'], 404);
        }

        $result=[
            'status' => 'success',
            'data' => $records,
            'message' => 'Projects retrieved successfully'
        ];
 
        return response()->json($records);
    }

    public function projectDetail(Request $request){
        $records = Project::where('id',$request->id)->first();
 
        return response()->json($records);
    }

    public function projectDetailData($id){
        $records = ProjectDetails::where('project_code',$id)->get();
 
        return response()->json($records);
    }

    public function projectShift($id){
        $records = ProjectShift::where('project_id',$id)->get();
 
        return response()->json($records);
    }

    public function deleteProjectShift($id){
        $deletedCount = ProjectShift::where('id', $id)->delete();

        return response()->json([
            'success' => true,
            'message' => $deletedCount > 0 ? 'Shifts successfully deleted!' : 'No shifts found to delete.',
            'deleted_count' => $deletedCount
        ]);
    }

    public function createProjectShift(Request $request){
        $validated = $request->validate([
            'project_id' => 'required',
            'shift_code' => 'required',
            'jam_masuk'  => 'required|date_format:H:i',
            'jam_pulang' => 'required|date_format:H:i',
        ]);

        $record = ProjectShift::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Shift berhasil ditambahkan!',
            'data'    => $record
        ]);
    }
}
