<?php

namespace App\Http\Controllers\Taskmanagement;

use App\Http\Controllers\Controller;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\ModelCG\Task;
use App\ModelCG\List_task;
use App\ModelCG\Project;
use App\ModelCG\Patroli;
use App\ModelCG\Schedule; 
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use PDF;

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
        $data['project_id']=Auth::user()->project_id;
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
        $data['detail']=Task::where('unix_code',$id)->first();
        return view('pages.operational.task.qrcode',$data);
       
    }

    public function report(Request $request){
        if(Auth::user()->project_id == NULL){
            $id_project = $request->input('project_id');
        }else{
            $id_project = Auth::user()->project_id;
        }

        $data['detail_project'] = Project::find($id_project);
        
        $data['project']=Project::all();
        $data['project_id']=$id_project;
        $data['client']=Auth::user()->project_id;
        $records = Project::all();
        $report = Task::where('project_id',$id_project)->get();
        if(!empty($report)){
            foreach($report as $row){
                $row->sub_task = List_task::where('id_master',$row->id)->get();
                $row->jml_sub = List_task::where('id_master',$row->id)->count();
            }
        }

        $periode = Carbon::now()->addMonth()->format('F-Y');
        $data['schedule'] = Schedule::where('periode', strtoupper($periode))
                                    ->where('project', $id_project)
                                    ->where('shift','!=','OFF')
                                    ->select('shift', DB::raw('count(*) as total'))
                                    ->groupBy('shift')
                                    ->get();

        $data['report']=$report;
        
        return view('pages.operational.task.report',$data);
    }

    public function download_qr($id){
        // Generate the QR code
        // Generate the QR code
        // Generate QR code with desired URL or text
        $image = QrCode::format('png')
    ->merge(public_path('1644463030.png'), 0.5, true)
    ->size(500)
    ->errorCorrection('H')
    ->generate('A simple example of QR code!');
        
        



        // Load the PDF view with the QR code data
        // $pdf = PDF::loadView('pages.operational.task.view_pdf', $data);

        // Stream the PDF to the browser
        // return $pdf->stream('qrcode.pdf');
        // return view('pages.operational.task.view_pdf', $data);
    }

    

}
