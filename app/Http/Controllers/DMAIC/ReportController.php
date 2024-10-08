<?php

namespace App\Http\Controllers\DMAIC;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
Use App\DMAIC;
Use App\DMAIC_Relations;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['report']=DMAIC::select('dmaic.*', 'karyawan.nama as nama_karyawan','dmaic_category.category_name')
                        ->join('karyawan', 'karyawan.id', '=', 'dmaic.nama')
                        ->join('dmaic_category', 'dmaic_category.id', '=', 'dmaic.category')
                        ->get();
        if(!empty($data['report'])){
            foreach($data['report'] as $row){
                $row->detail = DMAIC_Relations::join('dmaic_point', 'dmaic_point.id', '=', 'dmaic_relations.dmaic_point')
                                            ->where('dmaic_relations.dmaic_id',$row->id)
                                            ->get();
            }
        }

        // dd($data['report']);
        return view('pages.dmaic.report',$data);
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
