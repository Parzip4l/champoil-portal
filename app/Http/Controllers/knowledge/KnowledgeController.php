<?php

namespace App\Http\Controllers\Knowledge;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\ModelCG\Knowledge;
use App\ModelCG\Knowledge_soal;
use App\ModelCG\knowledge_jawaban;
use App\ModelCG\asign_test;
use App\ModelCG\jawaban_user;
use App\Employee;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class KnowledgeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $records = Knowledge::all();
        if($records){
            foreach($records as $row){
                $cek_asign = asign_test::where('id_test',$row->id)
                                        ->where('status',0)
                                        ->where('metode_training',"Offline")
                                        ->count();
                $row->count_cek = $cek_asign;
            }
        }

        return view('pages.hc.knowledge.index', compact('records'));
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
        $request->validate([
            'title' => 'required',
            'file_name' => 'required|file', // Add file validation rule
        ]);
    
        // Check if a file has been uploaded
        if ($request->hasFile('file_name')) {
            // Store the uploaded file and get its path
            $filePath = $request->file('file_name')->store('pulic/knowledge_base'); // 'uploads' is the directory where files will be stored
        } else {
            $filePath = null;
        }
    
        $knowledge = new Knowledge();
        $knowledge->title = $request->title;
        $knowledge->file_name = $filePath; // Save the file path
        $knowledge->save();

        return redirect()->route('knowledge_base')->with('success', 'Knowledge Successfully Added');
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
        $knowledge = Knowledge::find($id);
        $knowledge->delete();
        return redirect()->route('knowledge_base')->with('success', 'Contact Successfully Deleted');
    }

    public function add_soal($id){
        $data['value_master'] = Knowledge::find($id);
        return view('pages.hc.knowledge.add_soal',$data);
    }

    public function asign_user($id){
        
        $data['records']= Employee::where('organisasi','Frontline Officer')->get();
        $data['id_test']=$id;
        return view('pages.hc.knowledge.asign_user',$data);
    }

    public function read_test($id){
        
        $data['records']= Employee::where('organisasi','Frontline Officer')->get();
        $data['record']=Knowledge::where('id',$id)->first();
        $data['id_module']=$id;
        $data['file_module']=storage_path('app/'.$data['record']->file_name);
        
        
        return view('pages.hc.knowledge.read_test',$data);

    }

    public function pdfPreview($id){
        
        $data['records']= Employee::where('organisasi','Frontline Officer')->get();
        $data['record']=Knowledge::where('id',$id)->first();
        $data['id_module']=$id;
        $data['file_module']=storage_path('app/'.$data['record']->file_name);
        if (file_exists($data['file_module'])){
            $headers = [
                'Content-Type' => 'application/pdf',
            ];
            return response()->file($data['file_module'], $headers);
        } else {
            abort(404, 'File not found!');
        }
        // return view('pages.hc.knowledge.read_test',$data);

    }

    public function save_soal(Request $request){
        $data = $request->all();
        if($data['master_soal']){
            $no=1;
            $index=0;
            foreach ($data['master_soal'] as $row){
                $master_test=[
                    "master_test"=>$data['master_test'],
                    "master_soal"=>$data['master_soal'][$index]
                ];
                
                $get_id = Knowledge_soal::insertGetId($master_test);
                $no2=0;
                
                foreach ($data['jawaban_'.$no] as $row2){
                    $insert=[
                        "id_soal"=>$get_id,
                        "jawaban"=>$data['jawaban_'.$no][$no2],
                        "point"=>$data['point_'.$no][$no2]
                    ];
                    
                    knowledge_jawaban::insert($insert);
                    $no2++;
                }
                
                $index++;
                $no++;
            }
        }
        return redirect()->route('knowledge_base')->with('success', 'Contact Successfully Deleted');
    }
    
    public function save_asign_users(Request $request){
        $data = $request->all();
        if($data){
            $no=0;
            foreach($data['employee_code'] as $row){
                $notes_training=[
                    "lokasi"=>$data['lokasi'],
                    "tanggal"=>$data['tanggal'],
                    "jam"=>$data['jam'],
                    "catatan"=>$data['catatan']
                ];

                $data_insert =[
                    'employee_code'=>$data['employee_code'][$no],
                    'id_test'=>$data['id_test'],
                    "status"=>0,
                    "metode_training"=>$data['metode_training'],
                    "notes_training"=>json_encode($notes_training)
                ];


                asign_test::insert($data_insert);
                $no++;
            }
        }
        return redirect()->route('asign_user', ['id' => $data['id_test']])->with('success', 'Contact Successfully Deleted');
    }

    public function user_test($id){
        $data['id_module']=$id;
        $data['result']=[];
        $test = Knowledge::find($id);
        if($test){
            $soal = Knowledge_soal::where('master_test',$test->id)->inRandomOrder()->get();
            if($soal){
                foreach($soal as $row){
                    $jawaban = Knowledge_jawaban::where('id_soal',$row->id)->inRandomOrder()->get();
                    $row->jawaban = $jawaban;
                    $response[]=$row;
                }
                
            }
            $data['result']=$response;
        }

        $data['test']=$test;

        return view('pages.hc.knowledge.user_test',$data);
    }

    public function submit_user(Request $request){
        $data = $request->all();
        
        if($data){
            $count = count($data);
            $jml_test = $count-3;
            $point=0;
            for ($i = 0; $i <= $jml_test; $i++) {
                $explode = explode("-",$data['test_'.$i]);
                $id_soal = $explode[1];
                $id_jawaban = $explode[0];
                $cek_jawaban = knowledge_jawaban::where('id',$id_jawaban)->first();
                
                $insert=[
                    "id_soal"=>$id_soal,
                    "id_jawaban"=>$cek_jawaban->id,
                    "nilai_point"=>isset($cek_jawaban->point)?$cek_jawaban->point:0
                ];
                $point +=isset($cek_jawaban->point)?$cek_jawaban->point:0;
                
                jawaban_user::insert($insert);
            }
            
            asign_test::where('employee_code',Auth::user()->employee_code)
                        ->where('id_test',$data['id_module'])
                        ->update(["status"=>1,"total_point"=>$point]);
            
        }
        return redirect()->route('dashboard.index')->with('success', 'Submit Successfully');
    }
    public function list_classroom(){
        $data=[];
        $data['test_finis']=DB::connection('mysql_secondary')
                            ->table('asign_tests')
                            ->join('knowledge','asign_tests.id_test', '=', 'knowledge.id')
                            ->where('status',1)
                            ->where('employee_code',Auth::user()->employee_code)
                            ->select('knowledge.*','asign_tests.total_point','asign_tests.updated_at')
                            ->get();
        $data['asign_test'] = asign_test::where('employee_code',Auth::user()->employee_code)->where('status',0)->get();

        return view('pages.hc.knowledge.list_class',$data);
    }

    public function start_class($id){
        asign_test::where('id_test',$id)->update(['start_class'=>1]);
        return redirect()->route('knowledge_base')->with('success', 'Class Is Starting');
    }
}
