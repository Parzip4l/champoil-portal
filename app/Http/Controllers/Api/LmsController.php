<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

// models
use App\Employee;
use App\ModelCG\Knowledge;
use App\ModelCG\User_read_module;
use App\ModelCG\asign_test;

class LmsController extends Controller
{

    // Data LMS
    public function DataLearning(Request $request)
    {
        $token = $request->bearerToken();
        // Authenticate the user based on the token
        $user = Auth::guard('api')->user();

        $data=[];
        $data['finished_tes']=DB::connection('mysql_secondary')
                            ->table('asign_tests')
                            ->join('knowledge','asign_tests.id_test', '=', 'knowledge.id')
                            ->where('status',1)
                            ->where('employee_code', $user->employee_code)
                            ->select('knowledge.*','asign_tests.total_point','asign_tests.updated_at')
                            ->get();
        $data['my_tes'] = DB::connection('mysql_secondary')
                            ->table('asign_tests')
                            ->join('knowledge','asign_tests.id_test', '=', 'knowledge.id')
                            ->where('status',0)
                            ->where('employee_code', $user->employee_code)
                            ->select('knowledge.*','asign_tests.*')
                            ->get();

        return response()->json(['dataLearning' => $data], 200);
    }
    public function ReadTest($id,Request $request)
    {
        $redirect="";
        $error=false;
        $result=[];
        // try{
            // Retrieve the token from the request
            $token = $request->bearerToken();
            // Authenticate the user based on the token
            $user = Auth::guard('api')->user();

            $data['records'] = Employee::where('organisasi', 'Frontline Officer')->get();
            $data['record'] = Knowledge::where('id', $id)->first();
            $fileName="";
            
            if($data['record']){
                $fileName = $data['record']->file_name;
            }
            
            // Check if the record exists
            if (!$data['record']) {
                return response()->json(['error' => 'Record Not Found.'], 500);
                $error=true;
            }
        
            $data['id_module'] = $id;
            $data['file_module'] = $fileName;
            // Check if the file exists
            if (!($data['file_module'])) {
                return response()->json(['error' => 'File Not Found.'], 500);
                $error=true;
            }
        
            User_read_module::insert([
                "created_at" => now(),
                "employee_code" => $user->employee_code,
                "id_module" => $id
            ]);
        
            $cek = Asign_test::where('id_test', $id)
                ->where('employee_code', $user->employee_code)
                ->where('status', 0)
                ->first();
        
            if ($cek && $cek->module_read == 1) {
                $redirect = redirect()->route('kas/user.test', ['id' => $id])->with('success', 'Your Module Redirect');
                $error=true;
            } else {
                $url =$data['file_module'];
            }

        $result=[
            "error"=>$error,
            "redirect"=>$redirect,
            "filename"=>$url
        ];

        return response()->json($result);
    }

    private function get_soal($data)
    {

    }

}
