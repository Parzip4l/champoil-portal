<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\EmployeeResign;
use App\Absen;
use App\ModelCG\Project;
use App\Employee;


class EmployeeController extends Controller
{
    // resign kurang dari 30 hari
    public function resign(){
        $result=[];

        $records = EmployeeResign::all();
        if($records){
            foreach($records as $row){
                $row->project = "";
                $last_schdl = Absen::where('nik', $row->employee_code)->first();

                if($last_schdl){
                    $project = Project::where('id', $last_schdl->project)->first();
                    $row->project = $project->name;
                }

                // Tanggal awal
                $tanggal_awal = date('Y-m-d',strtotime($row->join_date));

                // Tanggal akhir
                $tanggal_akhir = date('Y-m-d',strtotime($row->created_at));

                // Konversi tanggal ke timestamp
                $timestamp_awal = strtotime($tanggal_awal);
                $timestamp_akhir = strtotime($tanggal_akhir);

                // Hitung selisih timestamp
                $selisih_hari = ($timestamp_akhir - $timestamp_awal) / (60 * 60 * 24);
                if($selisih_hari <= 30){
                    $result[]=$row;
                }
                
            }
        }

        return response()->json([
            'error'=>false,
            'result'=>$result    
        ]);
    }

    public function all_employee(){
        $records = Employee::where('resign_status',0)->get();
        $html='*Important Update for Truest App!*

Hello esteemed Truest users,
        
We would like to inform you that a critical update for the Truest app is now available. Please follow these steps promptly:
        
Google Play Store (Android):
https://play.google.com/store/apps/details?id=co.id.truest.truest
        
App Store (iOS):
https://apps.apple.com/idn/app/truest/id6476389232?platform=iphone
        
Thank you for your attention and cooperation. If you have any questions or issues, please feel free to contact our support team.';
        foreach($records as $row){
            push_notif_wa($html,'','',$row->telepon,'');
        }
        
        


        $result=[
            "records"=>$records,
            "message"=>"success",
            "error"=>false
        ];

        return response()->json($result);

    }

    public function turnover_statistik(){

        $year = date('Y');
        $month=[1,2,3,4,5,6,7,8,9,10,11,12];

        $records=[];

        foreach($month as $key=>$val){
            $records[] = EmployeeResign::whereYear('created_at', $year)
                                        ->whereMonth('created_at', $val)
                                        ->where('unit_bisnis','=','Kas')
                                        ->count();
        }

        

        $result=[
            "records"=>json_encode($records),
            "message"=>"success",
            "error"=>false
        ];

        return response()->json($result);
    }
}
