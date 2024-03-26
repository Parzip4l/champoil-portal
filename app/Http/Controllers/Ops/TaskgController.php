<?php

namespace App\Http\Controllers\Ops;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\ModelCG\Project; 
use App\ModelCG\TaskGlobal; 
use App\ModelCG\TaskParent; 


class TaskgController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['records']=TaskGlobal::all();
        if($data['records']){
            foreach($data['records'] as $row){
                if($row->repeat_task==1){
                    $row->repeat_task = "Hanya Satu Kali";
                }else if($row->repeat_task==2){
                    $row->repeat_task = "Harian";
                }else if($row->repeat_task==3){
                    $row->repeat_task = "Mingguan";
                }else if($row->repeat_task==4){
                    $row->repeat_task = "Bulanan";
                }

                if($row->upload_file == 1){
                    $row->upload_file = "Yes";
                }else{
                    $row->upload_file = "No";
                }

                if($row->project){
                    $project = Project::where('id',$row->project)->first();
                    $row->project = $project->name;
                }else{
                    $row->project = "All Project";
                }
            }
        }
        $data['project']=Project::all();
        return view('pages.hc.task_global.index',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $data=[];
        return view('pages.hc.task_global.create',$data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $request->validate([
            'unit_bisnis' => 'required',
            'task_name' => 'required',
            'upload_file'=>'required',
            'repeat_task'=>'required',
            
        ]);

        $taskg = new TaskGlobal();
        
        $taskg->unit_bisnis = $request->unit_bisnis;
        $taskg->project = $request->project;
        $taskg->task_name = $request->task_name;
        $taskg->upload_file = $request->upload_file;
        $taskg->repeat_task = $request->repeat_task;

        $taskg->save();
        
        return redirect()->route('taskg.index')->with('success', 'Task Successfully Added');

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
        $data=[];
        $data['master']=TaskGlobal::find($id);
        $data['records']=TaskParent::where('id_task',$id)->get();
        $data['project']=Project::all();
        return view('pages.hc.task_global.edit',$data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    public function save_item(Request $request){
        $item = $request->all();

        if($item['task_name']){
            $no=0;
            foreach($item['task_name'] as $row){
                $ins=[
                    "task_name"=>$item['task_name'][$no],
                    "upload_file"=>$item['file_upload'][$no],
                    "id_task"=>$item['id_master']
                ];

                if($item['id'][$no]==0){
                    TaskParent::insert($ins);
                }else{
                    TaskParent::where('id',$item['id'][$no])->update($ins);
                }
                

                $no++;
            }
        }

        return redirect()->route('task_edit',['id'=>$item['id_master']])->with('success', 'Task Successfully Added');
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
    }
}
