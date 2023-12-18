<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LmsController extends Controller
{
    public function read_test($id){
        $redirect="";
        $error=false;
        $result=[];

        $data['records'] = Employee::where('organisasi', 'Frontline Officer')->get();
        $data['record'] = Knowledge::where('id', $id)->first();
        $fileName="";
        
        if($data['record']){
            $fileName = $data['record']->file_name;
        }
        
        // Check if the record exists
        if (!$data['record']) {
            $redirect = redirect()->route('some_error_route')->with('error', 'Knowledge record not found');
            $error=true;
        }
    
        $data['id_module'] = $id;
        $data['file_module'] = $fileName;
        // Check if the file exists
        if (!($data['file_module'])) {
            $redirect = redirect()->route('some_error_route')->with('error', 'File not found');
            $error=true;
        }
    
        User_read_module::insert([
            "created_at" => now(),
            "employee_code" => Auth::user()->employee_code,
            "id_module" => $id
        ]);
    
        $cek = Asign_test::where('id_test', $id)
            ->where('employee_code', Auth::user()->employee_code)
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
            "url"=>$url
        ];

        return response()->json($result);
    }

    private function get_soal($data){

    }
}
