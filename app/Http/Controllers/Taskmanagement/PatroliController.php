<?php

namespace App\Http\Controllers\Taskmanagement;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\ModelCG\Task;
use App\ModelCG\List_task;
use App\ModelCG\Patroli;
use App\ModelCG\Absen;
use App\ModelCG\Temuan;
use App\Employee;
use App\ModelCG\Project;
use App\ModelCG\Shift;
use App\ModelCG\ProjectRelations;
use PDF;
use Illuminate\Support\Facades\Auth;

class PatroliController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data=[];
        return view('pages.operational.patroli.index',$data);
    }

    public function scan_qr()
    {
        $data=[];
        return view('pages.operational.patroli.scan_qr',$data);
    }

    public function checklist_task(Request $request,$params){
        $data=[];
        $data['message']="";

        $data['master']=Task::where('unix_code',$params)->first();
        

        // $lat = $request->input('lat');
        // $long = $request->input('long');
        // $kantorLatitude = $data['master']->latitude;
        // $kantorLongtitude = $data['master']->longitude;

        

        // $distance = $this->calculateDistance($kantorLatitude, $kantorLongtitude, $lat, $long);
        
        // $allowedRadius = 10000;
        // if ($distance <= $allowedRadius) {
            if($data['master']){
                $data['master']->list_task = List_task::where('id_master',$data['master']->id)->get();
            }
        // }else{
        //     $data['message']="Scan Rejected, Outside Radius!";
        // }

        return view('pages.operational.patroli.checklist',$data);
    }
    
    public function report(Request $request){
        $segments = $request->segments();
        $data['task']=Task::where('unix_code',$segments[1])->first();
        $data['list']=List_task::where('id_master',$data['task']->id)->get();
        if($data['list']){
            foreach($data['list'] as $row){
                $row->detail = Patroli::where('id_task',$row->id)->where('unix_code',$segments[1])->first();
                $row->petugas=[];
                if($row->detail){
                    $row->petugas = Employee::where('nik',$row->detail->employee_code)->first();
                }
            }
        }
        return view('pages.operational.patroli.report',$data);
    }
    

    public function post_code(Request $request){
        $result=[];
        $error=true;
        $msg="Data Empty";
        
        $data = $request->all();

        if($data){
            $error=false;
            $msg=url('checklist/'.$data['qr_code']);
        }

        $result=[
            "error"=>$error,
            "msg"=>$msg
        ];

        return response()->json($result);
    }

    public function store(Request $request){
        $data = $request->all();
        $get_shift = "";

        // insert looping to table patrolis
        $no=0;
        foreach($data['keterangan'] as $row){
            $explode = explode("-",$data['status'.$no]);
            $ins =[
                "id_task"=>$explode[1],
                "employee_code"=>Auth::user()->employee_code,
                "status"=>$explode[0],
                "unix_code"=>$data['unix_code'],
                "description"=>isset($data['keterangan'][$no])?$data['keterangan'][$no]:"-",
                "created_at"=>date('Y-m-d H:i:s')
            ];

            Patroli::insert($ins);

            $no++;
        }

        // insert looping to table temuan

        $no2=0;
        if(!empty($data['temuan'])){
            foreach($data['temuan'] as $temuan){
                $ins2=[
                    "temuan"=>$data['temuan'][$no2],
                    "tindakan"=>$data['tindakan'][$no2],
                    "shift"=>"",
                    "unix_code"=>$data['unix_code'],
                    "employee_code"=>Auth::user()->employee_code,
                ];
                Temuan::insert($ins2);
    
                $no2++;
            }
        }
        
        return redirect()->route('patroli')->with('success', 'Successfully');
        
    }

    private function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371; 

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLon / 2) * sin($dLon / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        $distance = round($earthRadius * $c); 
        return $distance;
    }

    public function download_report(Request $request){
        set_time_limit(300);
        $data['project'] = Project::find($request->input('project_id'));

        $explode = explode('-',$request->input('periode'));
        
        $data['kalender'] = tanggal_bulan($explode[1],date('m',strtotime($explode[0])));
        $data['task']=Task::where('project_id',$request->input('project_id'))->get();
       
        if($data['kalender']){
            foreach($data['kalender'] as $tgl){
                if($data['task']){
                    foreach($data['task'] as $row){
                        $row->list=List_task::where('id_master',$row->id)->get();
                        $row->count=List_task::where('id_master',$row->id)->count();
                        if($row->list){
                            foreach($row->list as $row2){
                                $row2->detail = Patroli::where('id_task',$row->id)->whereDate('created_at', $tgl)->get();
                                $row2->detail_count = Patroli::where('id_task',$row->id)->whereDate('created_at', $tgl)->count();
                                $row->count2 = ($row->count*2)+1;
                            }
                        }
                    }
                }

                // $pdf = PDF::loadView('pages.operational.patroli.download_pdf',$data)
                //   ->setPaper('a4', 'landscape');
                // $filePath = storage_path('app/public/reports/report_patroli_' . $data['project']->name . '.pdf');
                // $pdf->save($filePath);
            }
        }

        return response()->json($data);
        
    }

    public function analityc(){
        $data = [];
        $values = [];
        $year = 2024; // Ganti dengan tahun yang diinginkan
        $month = 9;   // Ganti dengan bulan yang diinginkan (1-12)

        // Mendapatkan jumlah hari dalam bulan tersebut
        $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);

        // Menyimpan semua tanggal dalam satu bulan
        $dates = [];
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $dates[] = date('Y-m-d', strtotime("$year-$month-$day"));
            $values[] = [10, 20]; // Assuming you want to store the same values for each date
        }

        // Menambahkan tanggal ke array $data untuk dikirim ke view
        $data['dates'] = json_encode($dates);
        $data['values'] = json_encode($values);

        return view('pages.operational.patroli.analityc', $data);
    }


    public function preview_test(Request $request){

        $project = Project::find($request->input('project'));
        // $explode = explode('-', $request->input('periode'));
        // $kalender = tanggal_bulan($explode[1], date('m', strtotime($explode[0])));
        $tasks = Task::where('project_id', $request->input('project'))->get();
        $jumlah_task = count($tasks);
        $project_relations = [];
        if($project){
            $project_relations = ProjectRelations::where('id_project',$project->id)->get();
            if($project_relations){
                foreach ($project_relations as $row) {
                   $row->shift = Shift::where('id',$row->id_shift)->first();
                }
            }
        }


        //create pdf


        $data = [
            'title' => 'Patrolis Analytic',
            'project' => strtoupper($project->name),
            'success' => true,
            // 'return' => $tasks,
            'jumlah_task' => $jumlah_task,
            'jumlah_patrol_pershift' => 3,
            'shift' => $project_relations
        ];
        

        return view('pages.operational.task.pdf_template',$data);
     
    }
    
}

