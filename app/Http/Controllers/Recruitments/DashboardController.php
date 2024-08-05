<?php

namespace App\Http\Controllers\Recruitments;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\ModelCG\JobApplpicant;
use App\EmployeeResign;
use App\Absen;
use App\ModelCG\Project;
use App\Employee;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $data=[];
        $result=[];
        $records = EmployeeResign::all();
        if($records){
            foreach($records as $row){
                $row->project = "";
                $last_schdl = Absen::where('nik', $row->employee_code)->first();

                if($last_schdl){
                    $project = Project::where('id', $last_schdl->project)->first();
                    $row->project = @$project->name;
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

        $data['resign30days'] =$result;
        
        return view('pages.recruitments.report',$data);
    }

    
}
