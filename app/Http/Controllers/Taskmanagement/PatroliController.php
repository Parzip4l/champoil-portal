<?php

namespace App\Http\Controllers\Taskmanagement;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\ModelCG\Task;
use App\ModelCG\List_task;
use App\ModelCG\Patroli;
use App\ModelCG\Absen;
use App\ModelCG\Temuan;
use App\Employee;
use Illuminate\Support\Facades\Auth;

class PatroliController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data=[];
        return view('pages.operational.patroli.index',$data);
    }

    public function scan_qr()
    {
        $data=[];
        return view('pages.operational.patroli.scan_qr',$data);
    }

    public function checklist_task($params){
        $data=[];

        $data['master']=Task::where('unix_code',$params)->first();

        if($data['master']){
            $data['master']->list_task = List_task::where('id_master',$data['master']->id)->get();
        }

        return view('pages.operational.patroli.checklist',$data);
    }
    
    public function report(Request $request){
        $segments = $request->segments();
        $data['task']=Task::where('unix_code',$segments[1])->first();
        $data['list']=List_task::where('id_master',$data['task']->id)->get();
        if($data['list']){
            foreach($data['list'] as $row){
                $row->detail = Patroli::where('id_task',$row->id)->where('unix_code',$segments[1])->first();
                $row->petugas=[];
                if($row->detail){
                    $row->petugas = Employee::where('nik',$row->detail->employee_code)->first();
                }
            }
        }
        return view('pages.operational.patroli.report',$data);
    }
    

    public function post_code(Request $request){
        $result=[];
        $error=true;
        $msg="Data Empty";
        
        $data = $request->all();

        if($data){
            $error=false;
            $msg=url('checklist/'.$data['qr_code']);
        }

        $result=[
            "error"=>$error,
            "msg"=>$msg
        ];

        return response()->json($result);
    }

    public function store(Request $request){
        $data = $request->all();
        $get_shift = "";

        // insert looping to table patrolis
        $no=0;
        foreach($data['keterangan'] as $row){
            $explode = explode("-",$data['status'.$no]);
            $ins =[
                "id_task"=>$explode[1],
                "employee_code"=>Auth::user()->employee_code,
                "status"=>$explode[0],
                "unix_code"=>$data['unix_code'],
                "description"=>isset($data['keterangan'][$no])?$data['keterangan'][$no]:"-",
                "created_at"=>date('Y-m-d H:i:s')
            ];

            Patroli::insert($ins);

            $no++;
        }

        // insert looping to table temuan

        $no2=0;
        if(!empty($data['temuan'])){
            foreach($data['temuan'] as $temuan){
                $ins2=[
                    "temuan"=>$data['temuan'][$no2],
                    "tindakan"=>$data['tindakan'][$no2],
                    "shift"=>"",
                    "unix_code"=>$data['unix_code'],
                    "employee_code"=>Auth::user()->employee_code,
                ];
                Temuan::insert($ins2);
    
                $no2++;
            }
        }
        
        return redirect()->route('patroli')->with('success', 'Successfully');
        
    }
}

