<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\GrowthM;
use App\UserActivities;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;

class GrowthController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $team = GrowthM::all();
        $latestRecord = GrowthM::latest()->first();

        $nextCodeNumber = 1;

        if ($latestRecord) {
            // Jika ada entri terbaru, ambil nomor urut dari kode terbaru, tambahkan 1
            $latestCode = $latestRecord->team_code;
            $latestCodeParts = explode('-', $latestCode);
            $nomorurut = end($latestCodeParts);
            $nomorurut = ltrim($nomorurut, '0');
            $lastCodeNumber = (int)$nomorurut;
            $nextCodeNumber = $lastCodeNumber + 1;
        }

        // Format ulang nomor urut dengan panjang 5 digit
        $formattedCodeNumber = str_pad($nextCodeNumber, 5, '0', STR_PAD_LEFT);
        $generatedCode = "AM-CHMP-$formattedCodeNumber";
        return view('pages.growth.index', compact('team', 'generatedCode'));
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
            'team_name' => 'required',
            'team_code' => 'required'
        ]);

        $uuid = Str::uuid()->toString();
        
        $growth = new GrowthM();
        $growth->id = $uuid;
        $growth->team_name = $request->input('team_name');
        $growth->team_code = $request->input('team_code');       
        $growth->save();

        // User Activity
        $inputData = $request->all();
        $uuid2 = Str::uuid()->toString();

        $userActivity = new UserActivities();
        $userActivity->id = $uuid2;
        $userActivity->modul = 'Growth_Team'; 
        $userActivity->id_item_modul = $growth->id = $uuid;
        $userActivity->username = Auth::user()->name; 
        $userActivity->action = 'Created';
        $userActivity->old_values = null; 
        $userActivity->new_values = json_encode($inputData);

        $userActivity->save();

        return redirect()->route('growth.index')->with('success', 'Team Succesfully Created');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $contact = ContactM::find($id);
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
