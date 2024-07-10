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

class PenempatanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $client = new Client();

        try {
            // Make a GET request to the API endpoint
            $response = $client->get('http://data.cityservice.co.id/cs/public/api/result-test');

            // Get the JSON response body as a string
            $body = $response->getBody()->getContents();

            // Decode the JSON string into an associative array
            $dataApi = json_decode($body, true);

            // Now you can use the $data array which contains the fetched data
            $data['records']=$dataApi;
        } catch (\Exception $e) {
            // Handle any errors that occur during the request
            $data['records']=[];
        }

        // dd($data['records']['records']);


        return view('pages.recruitments.penempatan.index',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        $client = new Client();

        try {
            // Make a GET request to the API endpoint
            $response = $client->get('http://data.cityservice.co.id/cs/public/api/penempatan-detail/'.$id);

            // Get the JSON response body as a string
            $body = $response->getBody()->getContents();

            // Decode the JSON string into an associative array
            $dataApi = json_decode($body, true);

            // Now you can use the $data array which contains the fetched data
            $data['record']=$dataApi;
        } catch (\Exception $e) {
            // Handle any errors that occur during the request
            $data['record']=$e;
        }

        $code = Auth::user()->employee_code;
        $company = Employee::where('nik', $code)->first();
        $data['jabatan'] = Jabatan::where('parent_category', $company->unit_bisnis)->get();
        $data['divisi'] = Divisi::where('company', $company->unit_bisnis)->get();
        $data['organisasi'] = Organisasi::where('company', $company->unit_bisnis)->get();
        $data['project'] = Project::all();
        $data['golongan'] = GolonganModel::where('company', $company->unit_bisnis)->get();
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
