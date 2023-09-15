<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\GrowthM;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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
        return view('pages.growth.index', compact('team'));
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
            'namapemohon' => 'required',
            'rw' => 'required',
            'permasalahan' => 'required',
            'urusan' => 'required',
            'usulan' => 'required',
            'lokasi' => 'required',
            'dokumen_pendukung' => 'required|max:5048',
            'status_pengajuan' => 'required'
        ]);

        $uuid = Str::uuid()->toString();
        
        $sibangenan = new Sibangenan();
        $sibangenan->id = $uuid;
        $sibangenan->namapemohon = $request->input('namapemohon');
        $sibangenan->rw = $request->input('rw');
        $sibangenan->permasalahan = $request->input('permasalahan');
        $sibangenan->urusan = $request->input('urusan');
        $sibangenan->suburusan = $request->input('suburusan');
        $sibangenan->usulan = $request->input('usulan');
        $sibangenan->lokasi = $request->input('lokasi');
        $sibangenan->status_pengajuan = $request->input('status_pengajuan');        
        $sibangenan->save();

        return redirect()->route('growth.index')->with('success', 'Pengajuan Berhasil Dibuat');
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
