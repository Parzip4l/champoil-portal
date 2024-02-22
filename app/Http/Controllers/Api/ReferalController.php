<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Employee;

class ReferalController extends Controller
{
    public function search_referal($string){
        $records = Employee::where('referal_code',$string)->first();

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
            "records"=>$records->nama
        ];

        return response()->json($result, 200);
    }
}
