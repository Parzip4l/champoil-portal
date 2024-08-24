<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\ModelCG\VoiceofGuardians;


class VoiceOfController extends Controller
{
    public function index(Request $request){

        $records = VoiceofGuardians::all();
        $result=[
            "msg"=>$msg,
            "error"=>$error,
            "records"=>@$records->nama
        ];

        return response()->json($result, 200);
    }
}
