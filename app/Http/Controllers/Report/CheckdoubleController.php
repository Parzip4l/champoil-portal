<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\ModelCG\Project;
use App\ModelCG\Schedule;
use App\ModelCG\ScheduleBackup;
use App\Absen;
use App\Employee;
use Carbon\Carbon;

class CheckdoubleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data['project']=Project::all();
        if($request->input('project')){
            $data['records'] = Absen::join('karyawan','karyawan.nik','=','absens.user_id')
                                ->join('schedules','schedules.employee','=','absens.user_id')
                                ->where('schedules.project',$request->input('project'))
                                ->where('schedules.periode',$request->input('periode'))
                                ->get();
        }else{
            $data['records']=[];
        }
        
        // if($data['records']){
        //     foreach($data['records'] as $row){
        //         $row->project_name = Project::where('id',$row->project)->first();
        //     }
        // }
        return view('pages.report.absen.double',$data);
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
