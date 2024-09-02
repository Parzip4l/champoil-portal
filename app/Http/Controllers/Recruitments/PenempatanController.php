<?php

namespace App\Http\Controllers\Recruitments;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use GuzzleHttp\Client;

use App\Employee;
use App\EmployeeResign;
use App\Absen;
use App\Backup\AbsenBackup;
use App\User;
use App\Payrolinfo\Payrolinfo;
use App\UserActivities;
use App\ModelCG\Jabatan;
use App\Divisi\Divisi;
Use App\Organisasi\Organisasi;
use App\ModelCG\Schedule;
use App\ModelCG\ScheduleBackup;
use App\ModelCG\Project;
use Carbon\Carbon;
use App\Absen\RequestAbsen;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Exports\EmployeeExport;
use App\Imports\EmployeeImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Setting\Golongan\GolonganModel;
use App\ModelCG\Penempatan;
use App\ModelCG\JobApplpicant;
use App\ModelCG\ApplicantHistory;
use App\ModelCG\AbsensiTraining;

class PenempatanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        $result=[];
        // dd($data['records']['records']);
        $history = JobApplpicant::limit(100)->orderBy('id','DESC')->get();
        
        if($history){
            foreach($history as $row){
                $penempatan = AbsensiTraining::where('user_id',$row->id)->where('status','H')->first();
                    if(!empty($penempatan)){
                        $row->penempatan = $penempatan;
                        $result[]=$row;
                    }
                    
                
            }
        }

        $return=[];
        if($result){
            foreach($result as $row){
                $cek = Employee::where('nik',$row->nomor_induk)->count();
                if($cek == 0){
                    $return[]=$row;
                }
            }
        }

        $data['records']=$return;


        return view('pages.recruitments.penempatan.index',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        $data['record']=JobApplpicant::find($id);

        $code = Auth::user()->employee_code;
        $company = Employee::where('nik', $code)->first();
        $data['jabatan'] = Jabatan::where('parent_category', $company->unit_bisnis)->get();
        $data['divisi'] = Divisi::where('company', $company->unit_bisnis)->get();
        $data['organisasi'] = Organisasi::where('company', $company->unit_bisnis)->get();
        $data['project'] = Project::all();
        $data['golongan'] = GolonganModel::where('company', $company->unit_bisnis)->get();
        $data['sertifikasi']=['TIDAK ADA','GADA PRATAMA','GADA MADYA','GADA UTAMA','LAINNYA'];
        return view('pages.recruitments.penempatan.create',$data);
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
