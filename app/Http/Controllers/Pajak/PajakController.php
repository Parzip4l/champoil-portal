<?php

namespace App\Http\Controllers\Pajak;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

// Model
use App\Pajak\Pajak;
use App\Pajak\PajakDetails;

class PajakController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pajak = Pajak::all();
        return view('pages.app-setting.pajak.index', compact('pajak'));
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
        try {
            // Validate the form data
            $validatedData = $request->validate([
                'status_pernikahan' => 'required',
                'ter_code' => 'required',
                'min_bruto' => 'required|numeric',
                'max_bruto' => 'required|numeric',
                'persentase' => 'required',
            ]);

            // Create a new Pajak instance
            $pajak = Pajak::create([
                'status_pernikahan' => $validatedData['status_pernikahan'],
                'ter_code' => $validatedData['ter_code'],
            ]);

            $pajakdetails = PajakDetails::create([
                'pajak_id' => $validatedData['ter_code'],
                'min_bruto' => $validatedData['min_bruto'],
                'max_bruto' => $validatedData['max_bruto'],
                'persentase' => $validatedData['persentase'],
            ]);

            // Redirect or respond as needed
            return redirect()->route('pajak.index')->with('success', 'Data updated successfully');
        } catch (\Exception $e) {
            // Handle the exception (e.g., log, redirect with error message, etc.)
            return redirect()->back()->with('error', 'An error occurred while saving the data. ' . $e->getMessage());
        }
    }

    public function pajakdetails($pajakid)
    {
        $pajak = PajakDetails::where('pajak_id',$pajakid)->get();
        $codename = 'Unknown';

        // Assign codename based on $pajakid value
        if ($pajakid === '45363') {
            $codename = 'TER A';
        } elseif ($pajakid === '45464') {
            $codename = 'TER B';
        } elseif ($pajakid === '45565') {
            $codename = 'TER C';
        }
        
        return view('pages.app-setting.pajak.details', compact('pajak','codename'));
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
