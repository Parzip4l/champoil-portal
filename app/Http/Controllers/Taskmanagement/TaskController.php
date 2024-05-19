<?php

namespace App\Http\Controllers\Taskmanagement;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\ModelCG\Task;
use App\ModelCG\Project;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if(Auth::user()->project_id == NULL){
            $task = Task::join('projects', 'master_tasks.project_id', '=', 'projects.id')
            ->select('master_tasks.*', 'projects.name as project_name');
            if($request->input('project_id')){
                $task->where('projects.id',$request->input('project_id'));
            }
        }else{
            $task= Task::join('projects', 'master_tasks.project_id', '=', 'projects.id')
            ->select('master_tasks.*', 'projects.name as project_name')
            ->where('projects.id',Auth::user()->project_id);
        }
        
        $data['records']=$task->get();
        $data['project']=Project::all();
        $data['project_id']=$request->input('project_id');
        return view('pages.operational.task.index',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'title' => 'required',
            'project_id' => 'required', 
        ]);

        $task = new Task();
        $task->judul = $request->title;
        $task->project_id = $request->project_id;
        $task->jam_mulai_shift_1 = $request->jam_mulai_shift_1;
        $task->jam_akhir_shift_1 = $request->jam_akhir_shift_1;
        $task->jam_mulai_shift_2 = isset($request->jam_mulai_shift_2)?$request->jam_mulai_shift_2:0;
        $task->jam_akhir_shift_2 = isset($request->jam_akhir_shift_2)?$request->jam_akhir_shift_2:0;
        $task->jam_mulai_shift_3 = isset($request->jam_mulai_shift_3)?$request->jam_mulai_shift_3:0;
        $task->jam_akhir_shift_3 = isset($request->jam_akhir_shift_3)?$request->jam_akhir_shift_3:0;
        $task->jam_mulai_shift_4 = isset($request->jam_mulai_shift_4)?$request->jam_mulai_shift_4:0;
        $task->jam_akhir_shift_4 = isset($request->jam_akhir_shift_4)?$request->jam_akhir_shift_4:0;
        $task->status = isset($request->status)?$request->status:0;
        $task->unix_code = $this->code_unix();
       
        $task->save();

        return redirect()->route('task.index',['project_id'=>$request->project_id])->with('success', 'Data Patrol Successfully Added');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $data=$request->all();
        $ins=[
            "longitude"=>$data['longitude'],
            "latitude"=>$data['latitude']
        ];
        Task::where('unix_code',$data['unix_code'])->update($ins);
        return redirect()->route('task.index')->with('success', 'List Task Successfully Updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $task = Task::find($id);
        $task->delete();
        return redirect()->route('task.index')->with('success', 'List Task Successfully Deleted');
    }

    public function code_unix(){
        $chars = "abcdefghijkmnopqrstuvwxyz023456789"; 
        srand((double)microtime()*1000000); 
        $i = 0; 
        $unix_code = '' ; 

        while ($i <= 7) { 
            $num = rand() % 33; 
            $tmp = substr($chars, $num, 1); 
            $unix_code = $unix_code . $tmp; 
            $i++; 
        } 

        return $unix_code;
    }

    public function qr_code($id){
        
        $data['unix_code']=$id;
        return view('pages.operational.task.qrcode',$data);
       
    }

    public function report(Request $request){
        $data['project']=Project::all();
        $data['project_id']=$request->input('project_id');
        return view('pages.operational.task.report',$data);
    }

}
