<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\EmployeeResign;


class EmployeeController extends Controller
{
    // resign kurang dari 30 hari
    public function resign(){
        $result=[];

        $records = EmployeeResign::all();
        if($records){
            foreach($records as $row){
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
}
