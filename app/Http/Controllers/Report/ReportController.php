<?php

namespace App\Http\Controllers\report;
use Illuminate\Support\Facades\DB;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\ModelCG\Project;
use App\ModelCG\Schedule;
use App\ModelCG\ScheduleBackup;
use App\Absen;


class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $start = date('Y-m-d');
        $end = date('Y-m-d');
        if(!empty($request->input('periode'))){
            $explode=explode('to',$request->input('periode'));
            if(!empty($explode[0])){
                $start = date('Y-m-d',strtotime($explode[0]));
            }elseif(!empty($explode[1])){
                $end = date('Y-m-d',strtotime($explode[1]));
            }
        }
        


        $project = Project::all();
        if($project){
            foreach($project as $row){
                $row->persentase_backup=0;
                $row->persentase_absen=0;
                $row->schedule = Schedule::where('project',$row->id)
                                           ->whereBetween('tanggal', [$start, $end])
                                           ->where('shift','!=','OFF')
                                           ->count();
                $row->schedule_backup = ScheduleBackup::where('project',$row->id)->whereBetween('tanggal', [$start, $end])->count();
                $row->absen = Absen::where('project', $row->id)
                                    ->whereBetween('tanggal', [$start, $end])
                                    ->count();
            
                if($row->absen > 0 && $row->schedule > 0){
                    $row->persentase_absen = round(($row->absen / $row->schedule) * 100,2);
                }
                
                $row->absen_backup = Absen::where('project_backup',$row->id)
                                            ->whereBetween('tanggal', [$start, $end])
                                            ->count();    
                if($row->absen_backup > 0){
                    $row->persentase_backup = round(($row->absen_backup / $row->schedule_backup) * 100,2);
                }

               
                
            }
        }

        $data['project']=$project;

        return view('pages.report.absen.index',$data);
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
