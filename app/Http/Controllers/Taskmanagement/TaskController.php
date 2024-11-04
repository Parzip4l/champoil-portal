<?php

namespace App\Http\Controllers\Taskmanagement;

use App\Http\Controllers\Controller;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use App\ModelCG\Task;
use App\ModelCG\List_task;
use App\ModelCG\Project;
use App\ModelCG\Patroli;
use App\ModelCG\Schedule; 
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PDF;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if(Auth::user()->project_id == NULL){
            $task = Task::join('projects', 'master_tasks.project_id', '=', 'projects.id')
            ->select('master_tasks.*', 'projects.name as project_name');
            if($request->input('project_id')){
                $task->where('projects.id',$request->input('project_id'));
            }
        }else{
            $task= Task::join('projects', 'master_tasks.project_id', '=', 'projects.id')
            ->select('master_tasks.*', 'projects.name as project_name')
            ->where('projects.id',Auth::user()->project_id);
        }
        
        $data['records']=$task->get();
        $data['project']=Project::all();
        $data['project_id']=Auth::user()->project_id;
        return view('pages.operational.task.index',$data);
    }

    public function import(Request $request){
        // Validate the uploaded file
        $request->validate([
            'file_excel' => 'required|file|mimes:xlsx,xls,csv',
        ]);

        // Load the spreadsheet
        $spreadsheet = IOFactory::load($request->file('file_excel'));

        // Get the active sheet
        $sheet = $spreadsheet->getActiveSheet();

        // Initialize an array to hold the data
        $data = [];

        // Loop through each row in the sheet
        foreach ($sheet->getRowIterator() as $row) {
            $rowData = [];
            $cellIterator = $row->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(false); // This will return all cells, even if they are not set
            foreach ($cellIterator as $cell) {
                $rowData[] = $cell->getValue();
            }
            $data[] = $rowData;
        }
        
        if(!empty($data)){
            $key=0;
            foreach($data as $row){
                if($key>0){
                    $insert=[
                        'judul' => "{$row[1]}",
                        'project_id' => $row[2], 
                        'status'=>1,
                        'unix_code'=>$this->code_unix()
                    ];
                    Task::insert($insert);
                }
                $key++;
            }
        }
        return redirect()->route('task.index',['project_id'=>$request->project_id])->with('success', 'Data Patrol Successfully Added');return response()->json($data);
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
        $request->validate([
            'title' => 'required',
            'project_id' => 'required', 
        ]);

        $task = new Task();
        $task->judul = $request->title;
        $task->project_id = $request->project_id;
        $task->jam_mulai_shift_1 = $request->jam_mulai_shift_1;
        $task->jam_akhir_shift_1 = $request->jam_akhir_shift_1;
        $task->jam_mulai_shift_2 = isset($request->jam_mulai_shift_2)?$request->jam_mulai_shift_2:0;
        $task->jam_akhir_shift_2 = isset($request->jam_akhir_shift_2)?$request->jam_akhir_shift_2:0;
        $task->jam_mulai_shift_3 = isset($request->jam_mulai_shift_3)?$request->jam_mulai_shift_3:0;
        $task->jam_akhir_shift_3 = isset($request->jam_akhir_shift_3)?$request->jam_akhir_shift_3:0;
        $task->jam_mulai_shift_4 = isset($request->jam_mulai_shift_4)?$request->jam_mulai_shift_4:0;
        $task->jam_akhir_shift_4 = isset($request->jam_akhir_shift_4)?$request->jam_akhir_shift_4:0;
        $task->status = isset($request->status)?$request->status:0;
        $task->unix_code = $this->code_unix();
       
        $task->save();

        return redirect()->route('task.index',['project_id'=>$request->project_id])->with('success', 'Data Patrol Successfully Added');
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
    public function update(Request $request)
    {
        $data=$request->all();
        $ins=[
            "longitude"=>$data['longitude'],
            "latitude"=>$data['latitude']
        ];
        Task::where('unix_code',$data['unix_code'])->update($ins);
        return redirect()->route('task.index')->with('success', 'List Task Successfully Updated');
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
        $task = Task::find($id);
        $task->delete();
        return redirect()->route('task.index')->with('success', 'List Task Successfully Deleted');
    }

    public function code_unix(){
        $chars = "abcdefghijkmnopqrstuvwxyz023456789"; 
        srand((double)microtime()*1000000); 
        $i = 0; 
        $unix_code = '' ; 

        while ($i <= 7) { 
            $num = rand() % 33; 
            $tmp = substr($chars, $num, 1); 
            $unix_code = $unix_code . $tmp; 
            $i++; 
        } 

        return $unix_code;
    }

    public function qr_code($id){
        
        $data['unix_code']=$id;
        $data['detail']=Task::where('unix_code',$id)->first();
        return view('pages.operational.task.qrcode',$data);
       
    }

    public function report(Request $request){
        if(Auth::user()->project_id == NULL){
            $id_project = $request->input('project_id');
        }else{
            $id_project = Auth::user()->project_id;
        }

        $periode_filter = $request->input('periode')?$request->input('periode'):'';

        $data['detail_project'] = Project::find($id_project);
        
        $data['project']=Project::where('company','Kas')->get();
        $data['project_id']=$id_project;
        $data['client']=Auth::user()->project_id;
        $records = Project::all();
        
        $report = Task::where('project_id',$id_project)->get();

        $point=[];
        $btn_download=[];
        $point_green=[];
        if(!empty($report)){
            foreach($report as $row){
                $row->sub_task = List_task::where('id_master',$row->id)->get();
                $row->jml_sub = List_task::where('id_master',$row->id)->count();
                
            }
        }
        if(!empty($periode_filter)){
            $periode = strtoupper($periode_filter);
        }else{
            $periode = Carbon::now()->addMonth()->format('F-Y');
        }

        
        $data['schedule'] = Schedule::where('periode', strtoupper($periode))
                                    ->where('project', $id_project)
                                    ->where('shift','!=','OFF')
                                    ->select('shift', DB::raw('count(*) as total'))
                                    ->groupBy('shift')
                                    ->get();
        if(!empty($report)){
            foreach($this->tanggal_tahun() as $tanggal){
                foreach($report as $row){
                    $count = Patroli::where('unix_code',$row->unix_code)->whereDate('created_at',$tanggal)->count();
                    if($count > 0){
                        $point_green[]=[
                            "id"=>$row->id,
                            "start"=>$tanggal,
                            "end"=>$tanggal,
                            "title"=>$row->judul
                        ];
                        
                    }else{
                        $point[]=[
                            "id"=>$row->id,
                            "start"=>$tanggal,
                            "end"=>$tanggal,
                            "title"=>$row->judul
                        ];
                    }
                    
                }
                
            }
        }

        $data['report']=$report;
        $data['periode'] = date('F',strtotime($periode_filter));
        $data['proj'] = $id_project;
        $data['point']=json_encode($point);
        $data['point_green']=json_encode($point_green);
        $data['btn_download']=json_encode($btn_download);
        // dd($data['point']);
        return view('pages.operational.task.report',$data);
    }

    public function download_qr($id){
        $result=[];
        $records = Task::where('project_id',$id)->get();
        if(!empty($records)){
            foreach($records as $row){
                $path = storage_path('app/public/qrcodes'); // Define the path to save the QR code

                // Ensure the directory exists
                if (!file_exists($path)) {
                    mkdir($path, 0755, true);
                }

                $filename = $row->unix_code.'.png';
                $filePath = $path . '/' . $filename;

                // Generate the QR code and save it to the specified path
                QrCode::format('png')->size(150)->generate($row->unix_code, $filePath);
            }
        }
        
        $project = Project::find($id);

        // Generate PDF using the view file 'pdf_view.blade.php' with the provided data
        $pdf = PDF::loadView('pages.operational.task.view_pdf', ['records' => $records])->setPaper([0, 0, 350, 420]);

        // Return the PDF as a download
        return $pdf->stream('Qr_patroli_'.$project->name.'.pdf','F');
        


        
    }

    private function tanggal_tahun(){
        $year = date('Y');

        // Tentukan awal dan akhir tahun
        $startDate = Carbon::createFromDate($year, 1, 1);
        $endDate = Carbon::createFromDate($year, 12, 31);

        // Buat periode tanggal dari awal hingga akhir tahun
        $period = CarbonPeriod::create($startDate, $endDate);

        // Loop melalui periode dan ambil setiap tanggal
        $dates = [];
        foreach ($period as $date) {
            $dates[] = $date->format('Y-m-d'); // Format tanggal sesuai kebutuhan
        }

        return $dates;
    }

    

}
