<?php

namespace App\Http\Controllers\CgControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\ModelCG\Shift;

class ShiftControllers extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $shifts = Shift::all();
        return view('pages.hc.kas.shift.index', compact('shifts'));
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
        try{
            $request->validate([
                'name' => 'required',
                'code' => 'required'
            ]);
        
            $data = new Shift();
            $data->code = $request->code;
            $data->name = $request->name;
            $data->save();

            return redirect()->route('shift.index')->with(['success' => 'Shift has been Succesfully Created!']);
        }catch (ValidationException $exception) {
            $errorMessage = $exception->validator->errors()->first(); // ambil pesan error pertama dari validator
            redirect()->route('shift.index')->with('error', 'Gagal menyimpan data. ' . $errorMessage); // tambahkan alert error
        }
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
        $data = Shift::find($id);
        $data->delete();
        return redirect()->route('shift.index')->with('success', 'Tax Successfully Deleted');
    }
}
