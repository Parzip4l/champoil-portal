<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\ModelCG\Project;
use App\User;
use App\Employee;

class Map extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $records = Project::all();
        if($records){
            foreach($records as $row){
                $data['records'][]=[
                    'lat' => $row->latitude, 
                    'lng' => $row->longtitude, 
                    'popup' => $row->name
                ];
            }
        }

        return view('pages.operational.map.index',$data);
    }

    public function map_frontline(Request $request)
    {
        $code = Auth::user()->employee_code;
        $company = Employee::where('nik', $code)->first();


        $data['project'] = Project::all();
        $records = User::select('users.*', 'karyawan.*')
                        ->join('karyawan', 'karyawan.nik', '=', 'users.name')
                        ->whereNotNull('longitude')
                        ->where('company',$company->unit_bisnis)
                        ->get();

        $data['records'] = [];

        if (!empty($request->input('project_id'))) {
            $project = Project::find($request->input('project_id'));
            if ($project) {
                $kordinat_project = [
                    'lat' => $project->latitude,
                    'lng' => $project->longtitude,
                    'popup' => $project->name,
                    "project"=>1
                ];
                $data['records'][] = $kordinat_project;

                $data['long'] = $project->longtitude;
                $data['lat'] = $project->latitude;

                if ($records) {
                    foreach ($records as $row) {
                        $lat = $row->latitude;
                        $long = $row->longitude;
                        $kantorLatitude = $project->latitude;
                        $kantorLongtitude = $project->longtitude;
        
                        
        
                        $distance = $this->calculateDistance($kantorLatitude, $kantorLongtitude, $lat, $long);
                        $data['records'][] = [
                            'lat' => $row->latitude,
                            'lng' => $row->longitude,
                            'popup' => $row->nama.' '.$distance.' KM',
                            "project"=>0
                        ];
                    }
                }
            }
        }else{
            if ($records) {
                foreach ($records as $row) {
                   
                    $data['records'][] = [
                        'lat' => $row->latitude,
                        'lng' => $row->longitude,
                        'popup' => $row->nama,
                        "project"=>0
                    ];
                }
                $data['long'] = '';
                $data['lat'] ='';
            }
        }

        

        

        $data['project_id'] = $request->input('project_id') ?: 0;

        // dd($data);

        return view('pages.operational.map.map_frontline', $data);
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

    public function update_domisili(Request $request){
        $data = $request->all();
        

        DB::table('users') // Specify the table name
            ->where('id', $data['user_id']) // Where clause to filter records
            ->update([
                "longitude" => $data['longitude'], // Update longitude
                "latitude" => $data['latitude']    // Update latitude
            ]);

        $return = DB::table('users')
        ->where('id', $data['user_id'])
        ->first();

        return response()->json($return);
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
