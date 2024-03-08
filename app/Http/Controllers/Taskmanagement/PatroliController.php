<?php

namespace App\Http\Controllers\Taskmanagement;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\ModelCG\Task;
use App\ModelCG\List_task;
use App\ModelCG\Patroli;
use App\ModelCG\Absen;
use App\ModelCG\Temuan;
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
                "description"=>isset($data['keterangan'][$no])?$data['keterangan'][$no]:"-",
                "created_at"=>date('Y-m-d H:i:s')
            ];

            Patroli::insert($ins);

            $no++;
        }

        // insert looping to table temuan

        $no2=0;
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
        return redirect()->route('patroli')->with('success', 'Successfully');
        
    }
}

