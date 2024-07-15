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

use App\ModelCG\Task;
use App\ModelCG\List_task;
use App\ModelCG\Patroli;
use App\ModelCG\Absen;
use App\ModelCG\Temuan;
use App\ModelCG\Status_patrol;
use App\ModelCG\Schedule;
use App\Employee;
use App\ModelCG\Project;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Support\Facades\Storage;

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
        $result_patrol=[];

        $get_user = Schedule::where('tanggal',$tanggal)
                            ->where('shift',$shift)
                            ->where('project',$project)
                            ->first();
        $user = Employee::where('nik',$get_user->employee)->first();
        $data_patrol = Patroli::where('employee_code',$user->nik)
                                ->where('id_task',$id_task)
                                ->whereDate('created_at',$tanggal)
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

    public function download_report(Request $request){
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

    
}
