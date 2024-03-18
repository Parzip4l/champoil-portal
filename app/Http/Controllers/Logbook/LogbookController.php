<?php

namespace App\Http\Controllers\Logbook;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LogbookController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['records']=[];
        return view('pages.logbook.index',$data);
    }

    public function tamu()
    {
        // Create a new Guzzle client instance
        $client = new Client();

        try {
            // Make a GET request to the API endpoint
            $response = $client->get('http://data.cityservice.co.id/log/public/api/tamu/'.Auth::user()->project_id);

            // Get the JSON response body as a string
            $body = $response->getBody()->getContents();

            // Decode the JSON string into an associative array
            $dataApi = json_decode($body, true);

            // Now you can use the $data array which contains the fetched data
            $data['records']=$dataApi['data'];
        } catch (\Exception $e) {
            // Handle any errors that occur during the request
            $data['records']=[];
        }

        return view('pages.logbook.index',$data);
    }

    public function barang()
    {
        // Create a new Guzzle client instance
        $client = new Client();

        try {
            // Make a GET request to the API endpoint
            $response = $client->get('http://data.cityservice.co.id/log/public/api/barang/'.Auth::user()->project_id);

            // Get the JSON response body as a string
            $body = $response->getBody()->getContents();

            // Decode the JSON string into an associative array
            $dataApi = json_decode($body, true);

            // Now you can use the $data array which contains the fetched data
            $data['records']=$dataApi['data'];
        } catch (\Exception $e) {
            // Handle any errors that occur during the request
            $data['records']=[];
        }

        return view('pages.logbook.barang',$data);
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
