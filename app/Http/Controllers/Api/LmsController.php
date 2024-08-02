<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;
use App\Employee;
use App\User;
use App\Absen\RequestAbsen;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\PersonalAccessToken;
use Carbon\Carbon;
use App\Company\CompanyModel;
use App\Absen\RequestType;

// models
use App\ModelCG\Knowledge;
use App\ModelCG\Knowledge_soal; 
use App\ModelCG\Knowledge_jawaban;
use App\ModelCG\user_read_module;
use App\ModelCG\asign_test;
use App\ModelCG\Jawaban_user;

class LmsController extends Controller
{

    // Data LMS
    public function DataLearning(Request $request)
    {
        $token = $request->bearerToken();
        // Authenticate the user based on the token
        $user = Auth::guard('api')->user();
        $result=[];
        $dataLearning = Knowledge::where('id',90)->get();
        if($dataLearning){
            foreach($dataLearning as $row){
                if(!empty($row->url_video) || !empty($row->file_name)){
                    $result[]=$row;
                }
            }
        }

        return response()->json(['dataLearning' => $result], 200);
    }

    public function ReadTest($id, Request $request)
    {
        $redirect = "";
        $error = false;
        $result = [];
        $url = "";
        $cek=[];
        $fullUrl="";

        // Retrieve the token from the request
        $token = $request->bearerToken();

        // Authenticate the user based on the token
        $user = Auth::guard('api')->user();

        try {
            $data['records'] = Employee::where('organisasi', 'Frontline Officer')->get();
            $data['record'] = Knowledge::where('id', $id)->first();
            $fileName = "";

            if ($data['record']) {
                $fileName = $data['record']->file_name;
            }

            // Check if the record exists
            if (!$data['record']) {
                return response()->json(['error' => true, 'redirect' => redirect()->route('some_error_route')->with('error', 'Knowledge record not found')]);
            }

            $data['id_module'] = $id;
            $data['file_module'] = $fileName;

            // Check if the file exists
            if (!$data['file_module']) {
                return response()->json(['error' => true, 'redirect' => redirect()->route('some_error_route')->with('error', 'File not found')]);
            }

            user_read_module::insert([
                "created_at" => now(),
                "employee_code" => $user->employee_code,
                "id_module" => $id
            ]);

            $cek = Asign_test::where('id_test', $id)
                ->where('employee_code', $user->employee_code)
                ->where('status', 0)
                ->first();

            if ($cek && $cek->module_read == 1) {
                $redirectUrl = route('kas/user.test', ['id' => $id]);
                return response()->json(['error' => true, 'redirect' => redirect()->to($redirectUrl)->with('success', 'Your Module Redirect')]);
            } else {
                $url = $data['file_module'];
                // Generate the full URL for the file
                if($data['record']->category =='youtube'){
                    $fullUrl = $data['record']->file_name;
                }else{
                    $fullUrl = asset("storage/{$url}");
                }
            }
        } catch (\Exception $e) {
            // Log the error or handle it appropriately
            $url = 0;
            $redirect = 0;
            $error = true;
            $auth = $user;
        }

        // Check if $cek is not null before accessing its properties
        $status = ($cek) ? $cek->module_read : 0;

        $result = [
            "error" => $error,
            "redirect" => $redirect,
            "status" => $status,
            "url" => $fullUrl,  // Include the full URL in the response
            "user" => $user
        ];

        return response()->json($result);

    }


    public function KnowledgeTest($id, Request $request){

        // Retrieve the token from the request
        $token = $request->bearerToken();
        // Authenticate the user based on the token
        $user = Auth::guard('api')->user();

        $data['id_module']=$id;
        $data['soal']=[];
        $response=[];
        Asign_test::where('id_test',$id)
                    ->where('employee_code',$user->employee_code)
                    ->where('status',0)
                    ->update(['module_read'=>1]);

        $test = Knowledge::find($id);
        if($test){
            $soal = Knowledge_soal::where('master_test',$test->id)->inRandomOrder()->limit(30)->get();
            if($soal){
                foreach($soal as $row){
                    $jawaban = Knowledge_jawaban::where('id_soal',$row->id)->inRandomOrder()->get();
                    $row->jawaban = $jawaban;
                    $response[]=$row;
                }
            }
            $data['soal']=$response;

        }

        $result =[
            "module"=>$test,
            "durasi_test"=>@$test->durasi,
            "soal"=>$data['soal']
        ];

        return response()->json($result, 200);
    }

    public function SubmitTest(Request $request){

        // Retrieve the token from the request
        $token = $request->bearerToken();
        // Authenticate the user based on the token
        $user = Auth::guard('api')->user();


        $data = $request->all();
        $error=false;
        $msg="Success";
        
        if($data){
            $count = count($data);
            $jml_test = $count-3;
            $point=0;
            for ($i = 0; $i <= $jml_test; $i++) {
                $explode = explode("-",$data['test_'.$i]);
                $id_soal = $explode[1];
                $id_jawaban = $explode[0];
                $cek_jawaban = Knowledge_jawaban::where('id',$id_jawaban)->first();

                //rumus bobot
                
                $insert=[
                    "id_soal"=>$id_soal,
                    "id_jawaban"=>$cek_jawaban->id,
                    "nilai_point"=>isset($cek_jawaban->point)?$cek_jawaban->point:0
                ];
                $point +=isset($cek_jawaban->point)?$cek_jawaban->point:0;
                
                Jawaban_user::insert($insert);
            }
            
            Asign_test::where('employee_code',$user->employee_code)
                        ->where('id_test',$data['id_module'])
                        ->update(["status"=>1,"total_point"=>$point]);
            
        }

        $result=[
            "error"=>$error,
            "msg"=>$msg
        ];

        return response()->json($result, 200);
    }

    public function hasilNilai(Request $request){
        $msg="";
        $error=false;

        // Retrieve the token from the request
        $token = $request->bearerToken();
        // Authenticate the user based on the token
        $user = Auth::guard('api')->user();

        $records = Asign_test::where('employee_code',$user->employee_code)->get();
        if($records){
            foreach($records as $row){
                $row->materi = Knowledge::where('id',$row->id_test)->first();
                $row->status_lulus = "Belum Mengisi";
                if($row->total_point < 70){
                    $row->status_lulus = "Tidak Lulus";
                }else if($row->total_point >= 70){
                    $row->status_lulus = "Lulus";
                }
                
            }
        }

        if($records){
            $error=false;
            $msg="Data Nilai";
        }else{
            $error=true;
            $msg="Data Empty";
        }

        $result=[
            "msg"=>$msg,
            "error"=>$error,
            "records"=>$records
        ];

        return response()->json($result, 200);

    }

    
}
