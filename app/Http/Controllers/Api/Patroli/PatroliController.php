<?php

namespace App\Http\Controllers\Api\Patroli;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\PersonalAccessToken;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use App\ModelCG\Task;
use App\ModelCG\List_task;
use App\ModelCG\Patroli;
use App\ModelCG\Absen;
use App\ModelCG\Temuan;
use App\ModelCG\Status_patrol;
use App\ModelCG\Schedule;
use App\Employee;
use App\ModelCG\Project;
use App\ModelCG\ProjectRelations;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Support\Facades\Storage;
use PDF;

class PatroliController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function checklist_task(Request $request,$params){
        $data=[];
        $data['message']="";

        $data['master']=Task::where('unix_code',$params)->first();
        $data['status']=Status_patrol::all();
        
        $lat = $request->input('latitude');
        $long = $request->input('longitude');
        $kantorLatitude = $data['master']->latitude;
        $kantorLongtitude = $data['master']->longitude;
        $distance = calculateDistance($kantorLatitude, $kantorLongtitude, $lat, $long);
        
        $allowedRadius = 5;
        // if ($distance <= $allowedRadius) {
        //     if($data['master']){
                $data['master']->list_task = List_task::where('id_master',$data['master']->id)->get();
                $data['distance']=$distance;
        // //     }
        // // } else {
        // //     $data['message']="Scan Rejected, Outside Radius!";
        // //     $data['distance']=$distance; // Perbaikan penulisan variabel distance
        // //     $data['lat']=$lat;
        // //     $data['long']=$long;
        // }

        return response()->json($data);
    }

    public function detail(Request $request){
        $result=[
            'error'=>false,
            'message'=>'success',
            'records'=>[
                            [
                                "tanggal" => "2024-06-23",
                                "check_point" => "Main Gate",
                                "petugas" => "John Doe",
                            ],
                            [
                                "tanggal" => "2024-06-23",
                                "check_point" => "Side Entrance",
                                "petugas" => "Jane Smith",
                            ],
                            [
                                "tanggal" => "2024-06-23",
                                "check_point" => "Back Gate",
                                "petugas" => "Alex Johnson",
                            ],
                        ]
        ];
        return response()->json($result);
    }

    public function list(Request $request){

        $token = $request->bearerToken();
        $user = Auth::guard('api')->user();
        $nik = $user->employee_code;
        $records=[];
        if($nik){
            $error=false;
            $schedule = Schedule::whereDate('tanggal',date('Y-m-d'))
                              ->where('employee',$nik)
                              ->first();
            if(!empty($schedule)){
                $get_task=Task::where('project_id',$schedule->project)
                                ->get();
                if(!empty($get_task)){
                    foreach($get_task as $parent){
                        $list = List_task::where('id_master',$parent->id)
                                         ->join('patrolis','patrolis.id_task','=','list_task.id')
                                         ->where('employee_code',$nik)
                                         ->get();
                        if(!empty($list)){
                            foreach($list as $record){
                                $record->judul = $parent->judul;
                                $record->images= "https://hris.truest.co.id/assets/images/logo/logodesktop.png";
                            }
                            $records=$list;
                        }
                    }
                }
            }
        }else{
            $error=true;
        }
        $result=[
            'error'=>$error,
            'message'=>'success',
            'records'=>$records
        ];
        return response()->json($result);
    }

    public function patroli_save(Request $request)
    {
        $data = $request->all();
        $user = Auth::guard('api')->user();
        $nik = $user->employee_code;
    
        if (isset($data['id']) && is_array($data['id'])) {
            $no = 0;
            foreach ($data['id'] as $id) {
                $image = "";
                $photoKey = "photo{$id}";
                
    
                if ($request->hasFile($photoKey)) {
                    $file = $request->file($photoKey)[0];
                    if ($file->isValid()) {
                        $filename = time() . '_' . $file->getClientOriginalName();
                        $file->move(public_path('/images/company_logo'), $filename);
                        $image = '/images/company_logo/' . $filename; // Simpan path relatif ke database
                    } else {
                        return response()->json(['status' => false, 'message' => 'Invalid file upload.'], 400);
                    }
                }
    
                $insert = [
                    "unix_code" => $data['unix_code'],
                    "id_task" => $id,
                    "status" => $data['status'][$no],
                    "employee_code" => $nik,
                    "description" => $data['keterangan'][$no],
                    "created_at" => now(),
                    "image" => $image
                ];
    
                Patroli::insert($insert);
                $no++;
            }

            $error=false;
            $status=true;
            $msg="Patroli Berhasil Dilakukan";
        } else {
            // return response()->json(['status' => false, 'message' => 'No task IDs provided.'], 400);
            $error=true;
            $status=false;
            $msg="Patroli Gagal Dilakukan";
        }
    
        $return = [
            "status" => $status,
            "message" => $msg
        ];
    
        return response()->json($return);
    }

    public function report_patrol(Request $request){
        $tanggal=$request->input('tanggal');
        $shift=$request->input('shift');
        $project=$request->input('project');
        $id_task=$request->input('id_task');

        $bulan = $request->input('bulan') ?? date('Y-m');



        $result_patrol=[];

        $get_user = Schedule::where('tanggal',$tanggal)
                            ->where('shift',$shift)
                            ->where('project',$project)
                            ->first();
        $user = Employee::where('nik',$get_user->employee)->first();
        $data_patrol = Patroli::where('employee_code', $user->nik)
                        ->where('id_task', $id_task)
                        ->whereDate('created_at', $tanggal)
                        ->whereMonth('created_at', date('m', strtotime($bulan)))
                        ->whereYear('created_at', date('Y', strtotime($bulan)))
                        ->get();

        if(!empty($data_patrol)){
            foreach($data_patrol as $row){
                $petugas = Employee::where('nik',$row->employee_code)->first();
                $row->format_tanggal = date('d F Y H:i:s',strtotime($row->created_at));
                $task_name = List_task::where('id',$id_task)->first();
                $row->task_name = $task_name->task;
                $row->petugas = $petugas->nama;
                if($row->status==1){
                    $label="Baik";
                }else{
                    $label="Kurang Baik";
                }

                $row->label_status = $label;
            }
        }

        $return =[
            "status"=>true,
            "records"=>$get_user,
            "user"=>$user,
            "data_patrol"=>$data_patrol,
            "message"=>"Success"
        ];



        return response()->json($return);
    }

    public function download_report(){
        // Initialize PhpSpreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
    
        // Example data initialization (replace with your actual data fetching logic)
        $project = Project::find($request->input('project_id'));
        $explode = explode('-', $request->input('periode'));
        $kalender = tanggal_bulan($explode[1], date('m', strtotime($explode[0])));
        $tasks = Task::where('project_id', $request->input('project_id'))->get();
    
        // Set header styles
        $sheet->getStyle('A1:H1')->applyFromArray([
            'font' => ['bold' => true],
            'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
        ]);
    
        // Output project name
        $sheet->setCellValue('A1', $project->name);
        $sheet->mergeCells('A1:H1');
    
        // Set table headers
        $sheet->setCellValue('A2', 'No');
        $sheet->setCellValue('B2', 'Task');
        
    
        // Example data writing
        $rowNumber = 3;
        $no = 1;
        foreach ($kalender as $tgl) {
           
            
            
            foreach ($tasks as $row) {
                $sheet->setCellValue('A' . $rowNumber, $no);
                $sheet->setCellValue('B' . $rowNumber, $row->judul);
    
                if ($row->list) {
                    $no2 = 1;
                    foreach ($row->list as $rs) {
                        $sheet->setCellValue('A' . ($rowNumber + 1), $no . '.' . $no2);
                        $sheet->setCellValue('B' . ($rowNumber + 1), $rs->task);
                        $rowNumber++;
    
                        if ($rs->detail) {
                            foreach ($rs->detail as $det) {
                                $sheet->setCellValue('A' . $rowNumber, ''); // No need for number again
                                $sheet->setCellValue('B' . $rowNumber, ''); // No need for task again
                                $sheet->setCellValue('C' . $rowNumber, ''); // No need for date again
                                $sheet->setCellValue('D' . $rowNumber, date('H:i:s', strtotime($det->created_at)));
                                $sheet->setCellValue('E' . $rowNumber, 'Status'); // Replace with actual status
                                $sheet->setCellValue('F' . $rowNumber, 'Keterangan'); // Replace with actual keterangan
                                $sheet->setCellValue('G' . $rowNumber, 'Foto'); // Replace with actual foto
                                $sheet->setCellValue('H' . $rowNumber, 'Petugas'); // Replace with actual petugas
                                $rowNumber++;
                            }
                        }
    
                        $no2++;
                    }
                }
    
                
            }
            $rowNumber++;
            $no++;
        }
    
        // Save Excel file
        $writer = new Xlsx($spreadsheet);
        Storage::makeDirectory('reports');
        $fileName = 'project_' . str_replace(' ','_', $project->name) . '_report'.date('ymdhis').'.xlsx'; // Example file name
        $filePath = storage_path('app/reports/' . $fileName); // Example storage path
    
        try {
            $writer->save($filePath);
        } catch (\PhpOffice\PhpSpreadsheet\Writer\Exception $e) {
            return response()->json(['error' => 'Failed to save Excel file: ' . $e->getMessage()], 500);
        }
    
        // Return response with file path
        return response()->json(['message' => 'Excel file saved successfully', 'path' => $filePath]);
    }


    public function report_patroli(Request $request){
        $user = $request->user('api');

        $project = $request->input('project_id');
        $bulan = $request->input('bulan') ?? date('Y-m');
        

        $periode_filter = $request->input('periode')?$request->input('periode'):'';

        $data['detail_project'] = Project::find($project);
        
        $data['project']=Project::all();
        $data['project_id']=$project;
        $data['client']=$project;


        $records = Project::all();
        $report = Task::where('project_id',$project)->get();
        $jml_point = Task::where('project_id',$project)->count();
        $jml_shift = ProjectRelations::where('id_project',$project)->whereNotIn('id_shift',[13,60])->count();

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
                                    ->where('project', $project)
                                    ->where('shift','!=','OFF')
                                    ->select('shift', DB::raw('count(*) as total'))
                                    ->groupBy('shift')
                                    ->get();
        $patroli_ok=0;
        $patroli_nnot=0;
        if(!empty($report)){
            foreach($this->tanggal_tahun($bulan) as $tanggal){
                foreach($report as $row){
                    $count = Patroli::where('unix_code',$row->unix_code)->whereDate('created_at',$tanggal)->count();
                    if($count > 0){
                        $point_green[]=[
                            "id"=>$row->id,
                            "start"=>$tanggal,
                            "end"=>$tanggal,
                            "title"=>$row->judul
                        ];
                        $patroli_ok +=$count;
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
        $data['jml_shift']=$jml_shift;
        $data['jml_point']=$jml_point;
        $data['patroli_ok']=$patroli_ok;
        $data['jumlah_hari']=count($values);

        $data['report']=$report;
        $data['periode'] = date('F',strtotime($periode_filter));
        $data['proj'] = $project;
        $data['point']=$point;
        $data['point_green']=$point_green;
        $data['btn_download']=json_encode($btn_download);
        return response()->json($data);
    }

    private function tanggal_tahun($bulan = ""){
        if ($bulan) {
            // If $bulan is provided, extract the year and month from it
            $year = date('Y', strtotime($bulan));
            $month = date('m', strtotime($bulan));

            // Create start and end dates for the specific month
            $startDate = Carbon::createFromDate($year, $month, 1);
            $endDate = Carbon::createFromDate($year, $month, 1)->endOfMonth();
        } else {
            // If no $bulan is provided, use the current year and generate the full year range
            $year = date('Y');
            $startDate = Carbon::createFromDate($year, 1, 1);
            $endDate = Carbon::createFromDate($year, 12, 31);
        }

        // Create a period from the start date to the end date
        $period = CarbonPeriod::create($startDate, $endDate);

        // Collect all dates within the period
        $dates = [];
        foreach ($period as $date) {
            $dates[] = $date->format('Y-m-d');
        }

        return $dates;
    }


    public function export_report(){
        $data = [
            'title' => 'hjhj',
            'project' => 'Stella',
            'shift' => '',
            'jumlah_task' => ''
            // Add more data as needed
        ];
    
        $pdf = PDF::loadView('pages.operational.task.pdf_template', $data); // Pass the data array to the Blade view
    
        // Define the path where the PDF should be saved
        $filePath = public_path('patroli/sample.pdf');
        
        // Save the PDF to the specified path
        $pdf->save($filePath);
    
        // Optionally, return a response or redirect
        return response()->json(['message' => 'PDF saved successfully', 'path' => $filePath]);
    }

    public function saveChartImage(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'image' => 'required|string', // Image is expected to be a base64 encoded string
        ]);

        // Extract the base64 encoded image string
        $imageData = $request->input('image');

        // Remove the prefix (e.g., "data:image/png;base64,") from the string
        $imageData = str_replace('data:image/png;base64,', '', $imageData);
        $imageData = str_replace(' ', '+', $imageData);

        // Decode the base64 string
        $image = base64_decode($imageData);

        // Define the path where the image will be saved
        $filePath = public_path('patroli/chart_image.png');

        // Save the decoded image to the file path
        File::put($filePath, $image);

        // Return a response indicating success
        return response()->json(['success' => true, 'path' => $filePath]);
    }
    
}
