<?php

namespace App\Http\Controllers\Recruitments;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use GuzzleHttp\Client;

use App\ModelCG\JobApplpicant;
use App\ModelCG\ApplicantHistory;

class TestController extends Controller
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
            $records = JobApplpicant::whereBetween('tanggal', [$start, $end])->orderBy('id','desc')->get();
            if($records->isNotEmpty()) {
                foreach($records as $row) {
                    $history = ApplicantHistory::where('nik', $row->id)->first(); // Assuming 'nik' is the field to match
                    if ($history) {
                        $row->history = $history;
                        if($history->kualifikasi==1){
                            $data['records'][]=$row;
                        }
                    } else {
                        $row->kualifikasi = null; // or some default value
                    }
                    
                }
            }
        }else{
            $data['records']=[];
        }

        // dd($data['records']['records']);


        return view('pages.recruitments.test.index',$data);
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
