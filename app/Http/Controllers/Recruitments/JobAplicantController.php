<?php

namespace App\Http\Controllers\Recruitments;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use App\ModelCG\JobApplpicant;
use App\ModelCG\ApplicantHistory;

class JobAplicantController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(!empty($_GET['tanggal'])){
            $tanggal = str_replace(" ","",$_GET['tanggal']  );
            $explode = explode('to',$tanggal);
            $start=$explode[0];
            $end=$explode[1];
            $data['records'] = JobApplpicant::whereBetween('tanggal', [$start, $end])->orderBy('id','desc')->get();
            if($data['records']->isNotEmpty()) {
                foreach($data['records'] as $row) {
                    $history = ApplicantHistory::where('nik', $row->id)->first(); // Assuming 'nik' is the field to match
                    if ($history) {
                        $row->kualifikasi = $history->kualifikasi;
                    } else {
                        $row->kualifikasi = null; // or some default value
                    }

                    if($row->jenis_kelamin=="Pria"){
                        if($row->tb < 167){
                            $row->lolos_tb ="<span  style='color:red'><i class='link-icon' data-feather='x'></i></span>";
                        }else{
                            $row->lolos_tb ="<span style='color:green'><i class='link-icon' data-feather='check'></i></span>";
                        }

                        if($row->usia < 19 || $row->usia > 40){
                            $row->lolos_usia ="<span  style='color:red'><i class='link-icon' data-feather='x'></i></span>";
                        }else{
                            $row->lolos_usia ="<span  style='color:green'><i class='link-icon' data-feather='check'></i></span>";
                        }

                        $height = $row->tb / 100;
                        $bmi = $row->bb / ($height * $height);
                        $bmi_round = round($bmi,1);
                        if($bmi_round >= 18.5 && $bmi_round <= 30){
                            $row->lolos_bmi = "<span  style='color:green'><i class='link-icon' data-feather='check'></i></span>";
                        }else{
                            $row->lolos_bmi = "<span  style='color:red'><i class='link-icon' data-feather='x'></i></span>";
                        }
                        

                    }else{
                        if($row->tb < 167){
                            $row->lolos_tb ="<span  style='color:red'><i class='link-icon' data-feather='x'></i></span>";
                        }else{
                            $row->lolos_tb ="<span  style='color:green'><i class='link-icon' data-feather='check'></i></span>";
                        }

                        if($row->usia < 19 || $row->usia > 40){
                            $row->lolos_usia ="<span  style='color:red'><i class='link-icon' data-feather='x'></i></span>";
                        }else{
                            $row->lolos_usia ="<span  style='color:green'><i class='link-icon' data-feather='check'></i></span>";
                        }

                        $height = $row->tb / 100;
                        $bmi = $row->bb / ($height * $height);
                        $bmi_round = round($bmi,1);
                        if($bmi_round >= 18.5 && $bmi_round <= 27.5){
                            $row->lolos_bmi = "<span  style='color:green'><i class='link-icon' data-feather='check'></i></span>";
                        }else{
                            $row->lolos_bmi = "<span  style='color:red'><i class='link-icon' data-feather='x'></i></span>";
                        }
                    }
                }
            }
        }else{
            $data['records']=[];
        }

        

        return view('pages.recruitments.index',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
