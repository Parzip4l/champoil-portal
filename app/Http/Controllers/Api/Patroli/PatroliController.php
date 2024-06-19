<?php

namespace App\Http\Controllers\Api\Patroli;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\ModelCG\Task;
use App\ModelCG\List_task;
use App\ModelCG\Patroli;
use App\ModelCG\Absen;
use App\ModelCG\Temuan;
use App\ModelCG\Status_patrol;

class PatroliController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function checklist_task(Request $request,$params){
        $data=[];
        $data['message']="";

        $data['master']=Task::where('unix_code',$params)->first();
        $data['status']=Status_patrol::all();
        
        $lat = $request->input('latitude');
        $long = $request->input('longitude');
        $kantorLatitude = $data['master']->latitude;
        $kantorLongtitude = $data['master']->longitude;
        $distance = calculateDistance($kantorLatitude, $kantorLongtitude, $lat, $long);
        
        $allowedRadius = 5;
        if ($distance <= $allowedRadius) {
            if($data['master']){
                $data['master']->list_task = List_task::where('id_master',$data['master']->id)->get();
                $data['distance']=$distance;
            }
        } else {
            $data['message']="Scan Rejected, Outside Radius!";
            $data['distance']=$distance; // Perbaikan penulisan variabel distance
            $data['lat']=$lat;
            $data['long']=$long;
        }

        return response()->json($data);
    }

    public function patroli_save(Request $request){

        $data = $request->all();
        $token = $request->bearerToken();
        $user = Auth::guard('api')->user();
        $nik = $user->employee_code;

        if($data['id']){
            $no=0;
            foreach($data['id'] as $row){
                $insert=[
                    "unix_code"=>$data['unix_code'],
                    "id_task"=>$row['id'][$no],
                    "employee_code"=>$user->id
                ];
                $no++;
            }
        }



        return response()->json($data);
    }
}
