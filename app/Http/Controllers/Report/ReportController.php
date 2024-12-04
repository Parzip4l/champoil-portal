<?php

namespace App\Http\Controllers\report;
use Illuminate\Support\Facades\DB;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\ModelCG\Project;
use App\ModelCG\Schedule;
use App\ModelCG\ScheduleBackup;
use App\Absen;
use App\Employee;
use Carbon\Carbon;
use App\Absen\RequestAbsen;


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
            $periode = $request->input('periode');
            // Tambahkan satu bulan ke periode
            $periode_min_1_month = date('m', strtotime("-1 month", strtotime($periode)));

            // Ambil bulan dari periode yang sudah ditambahkan satu bulan
            $bulan = date('m', strtotime($periode));

            $start = date('Y').'-'.$periode_min_1_month.'-'.'21';
            $end = date('Y').'-'.$bulan.'-'.'20';

            
        }

        if(!empty($request->input('tanggal'))){
            $tanggal = $request->input('tanggal');
            $explode = explode(' to ',$tanggal);
            $start=$explode[0];
            $end=$explode[1];
           
        }
        $percen_50=0;
        $percen_50_80=0;
        $percen_80_99=0;
        $percen_100=0;
        $project = Project::where('deleted_at',NULL)->where('company','Kas')->get();
        if($project){
            foreach($project as $row){
                $row->persentase_backup=0;
                $row->persentase_absen=0;
                $row->persentase_tanpa_clockout = 0;
                $row->schedule = Schedule::where('project',$row->id)
                                           ->whereBetween('tanggal', [$start, $end])
                                           ->where('shift','!=','OFF')
                                           ->count();

                $row->schedule_backup = ScheduleBackup::where('project',$row->id)->whereBetween('tanggal', [$start, $end])->count();
              

                $row->absen = Absen::where('project', $row->id)
                                    ->whereBetween('tanggal', [$start, $end])
                                    ->count();
                
                $row->tanpa_clockout = Absen::where('project', $row->id)
                                    ->whereBetween('tanggal', [$start, $end])
                                    ->whereNotNull('clock_in')
                                    ->whereNull('clock_out')
                                    ->count();
                $row->need_approval = Schedule::join('requests_attendence','requests_attendence.employee','=','schedules.employee')
                                                    ->where('schedules.project',$row->id)
                                                    ->whereBetween('schedules.tanggal', [$start, $end])
                                                    ->whereBetween('requests_attendence.tanggal', [$start, $end])
                                                    ->where('requests_attendence.aprrove_status','Pending')
                                                    ->count();
                                                    
                $row->approved = Schedule::join('requests_attendence','requests_attendence.employee','=','schedules.employee')
                                            ->where('schedules.project',$row->id)
                                            ->whereBetween('schedules.tanggal', [$start, $end])
                                            ->whereBetween('requests_attendence.tanggal', [$start, $end])
                                            ->where('requests_attendence.aprrove_status','Approved')
                                            ->count();

                // Persentase Tanpa Clockout
                if ($row->absen > 0) {
                    $row->persentase_tanpa_clockout = round(($row->tanpa_clockout / $row->absen) * 100, 2);
                } else {
                    $row->persentase_tanpa_clockout = 0;
                }
            
                if($row->absen > 0 && $row->schedule > 0){
                    $row->persentase_absen = round(($row->absen / $row->schedule) * 100,2);
                    if($row->persentase_absen <= 50 ){
                        $percen_50 +=1;
                    }else if($row->persentase_absen >50 && $row->persentase_absen <= 80 ){
                        $percen_50_80 +=1;
                    }else if($row->persentase_absen >80 && $row->persentase_absen <= 99 ){
                        $percen_80_99 +=1;
                    }else if($row->persentase_absen >= 100 ){
                        $percen_100 +=1;
                    }
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
        $data['percent']=[
            $percen_50,
            $percen_50_80,
            $percen_80_99,
            $percen_100
        ];
        return view('pages.report.absen.index',$data);
    }

    public function rekap_report(){
        $project = Project::where('deleted_at',NULL)->where('company','Kas')->get();
        if($project){
            foreach($project as $row){
                $row->persentase_backup=0;
                $row->persentase_absen=0;
                $row->persentase_tanpa_clockout = 0;
                foreach(bulan() as $bln){
                    $month = strtoupper($bln."-".date('Y'));
                    $periode_min_1_month = date('m', strtotime("-1 month", strtotime($bln)));

                    // Ambil bulan dari periode yang sudah ditambahkan satu bulan
                    $bulan = date('m', strtotime($bln));

                    $start = date('Y').'-'.$periode_min_1_month.'-'.'21';
                    $end = date('Y').'-'.$bulan.'-'.'20';

                    $row['schedule'.$bln] = Schedule::where('project',$row->id)
                    ->whereBetween('tanggal', [$start, $end])
                    ->where('shift','!=','OFF')
                    ->count();

                    if(date('m', strtotime("+1 month")) == date('m',strtotime($bln))){
                        $row['on_periode'.$bln]=1;
                    }else{
                        $row['on_periode'.$bln]=0;
                    }
               
                    $row['absen'.$bln] = Absen::where('project', $row->id)
                                        ->whereBetween('tanggal', [$start, $end])
                                        ->count();
                
                    if($row['absen'.$bln] > 0 && $row['schedule'.$bln] > 0){
                        $row['persentase_absen'.$bln] = round(($row['absen'.$bln] / $row['schedule'.$bln]) * 100,2);
                    }
                }
                
              
            }
        }
        $data['project']=$project;
        return view('pages.report.absen.rekap',$data);
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
    public function show($id,$periode)
    {
        //
        $start = date('Y-m-d');
        $end = date('Y-m-d');

        if (!empty($periode)) {
        $explode = explode('to', $periode);

        if (!empty($explode[0])) {
            $start = date('Y-m-d', strtotime($explode[0]));
        }

        if (!empty($explode[1])) {
            $end = date('Y-m-d', strtotime($explode[1]));
        }
        }

        $records = Absen::where('project', $id)
            ->whereBetween('tanggal', [$start, $end])
            ->get();

        foreach ($records as $record) {
        
            $employee = Employee::where('nik', $record->nik)->first();
            if ($employee) {
                $record->nama_karyawan = $employee->nama;
            } else {
                $record->nama_karyawan = 'Unknown';
            }

            $schedule = Schedule::where('employee', $record->nik)
                ->whereDate('tanggal', $record->tanggal)
                ->first();

                if ($schedule) {
                    $record->shift = $schedule->shift; 
                } else {
                    $record->shift = 'Unknown';
                }
        }

        // if ($records) {
        // foreach ($records as $row) {
        //     $currentDate = Carbon::parse($start); // Use Carbon library for date manipulation

        //     while ($currentDate->lte($end)) {
        //         // Cari data absen untuk tanggal saat ini
        //         $attendanceData = Absen::whereDate('tanggal', $currentDate->format('Y-m-d'))->first();

        //         // Buat array data untuk tanggal ini
        //         $row->tanggal = $currentDate->format('Y-m-d');
        //         $row->clock_in = $attendanceData ? $attendanceData->clock_in : '-';
        //         $row->clock_out = $attendanceData ? $attendanceData->clock_out : '-';
        //         $row->status = $attendanceData ? $attendanceData->status : '-';

        //         $currentDate->addDay(); // Increment the current date
        //     }
        // }
        // }
        return view('pages.report.absen.detail',compact('records'));
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
