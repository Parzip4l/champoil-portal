<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\ModelCG\Project;
use App\User;
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
            ->where('name', $data['user_id']) // Where clause to filter records
            ->update([
                "longitude" => $data['longitude'], // Update longitude
                "latitude" => $data['latitude']    // Update latitude
            ]);

        $return = DB::table('users')
        ->where('name', $data['user_id'])
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
