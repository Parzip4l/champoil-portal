<?php

namespace App\Http\Controllers\Api\Patroli;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\PersonalAccessToken;
use Carbon\Carbon;

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

    public function detail(Request $request){
        $result=[
            'error'=>false,
            'message'=>'success',
            'records'=>[
                            [
                                "tanggal" => "2024-06-23",
                                "check_point" => "Main Gate",
                                "petugas" => "John Doe",
                            ],
                            [
                                "tanggal" => "2024-06-23",
                                "check_point" => "Side Entrance",
                                "petugas" => "Jane Smith",
                            ],
                            [
                                "tanggal" => "2024-06-23",
                                "check_point" => "Back Gate",
                                "petugas" => "Alex Johnson",
                            ],
                        ]
        ];
        return response()->json($result);
    }

    public function list(Request $request){

        $records=[];
        $token = $request->bearerToken();
        $user = Auth::guard('api')->user();
        $nik = $user;

        $result=[
            'error'=>$user,
            'message'=>'success',
            'records'=>''
        ];
        return response()->json($result);
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
                    "id_task"=>$data['id'][$no],
                    "employee_code"=>$user->id
                ];
                $no++;
            }
        }

        $return =[
            "status"=>true,
            "message"=>"Patroli Berhasil di Simpan"
        ];



        return response()->json($return);
    }
}
