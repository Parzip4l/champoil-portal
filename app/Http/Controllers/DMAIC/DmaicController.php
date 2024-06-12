<?php

namespace App\Http\Controllers\DMAIC;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
Use App\DMAIC;
Use App\DMAIC_Relations;
Use App\DMAICCategory;
Use App\DMAICPoint;
use App\Employee;
use App\ModelCG\Project;

class DmaicController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
        $data['category']=DMAICCategory::all();
        $data['points']=DMAICPoint::all();
        $data['employee']=Employee::where('unit_bisnis','Kas')->where('organisasi','Frontline Officer')->get();
        $data['project']=Project::all();
        return view('pages.dmaic.create',$data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $dmaic=[
            "nama"=>$data['nama'],
            "tanggal"=>date('Y-m-d H:i:s'),
            "project"=>$data['project'],
        ];

        $dmic_insert = DMAIC::create($dmaic);
        if($dmic_insert->id){
            $no=0;
            foreach($data['dmaic_point'] as $row){
                $parent=[
                    "dmaic_id"=>$dmic_insert->id,
                    "dmaic_point"=>$data['dmaic_point'][$no],
                    "created_at"=>date('Y-m-d H:i:s'),
                    "dmaic_value"=>$data['dmaic_value'][$no]
                ];

                DMAIC_Relations::insert($parent);

                $no++;
            }
        }

        return redirect()->route('dmaic-success')->with(['success' => 'DMAIC Berhasil Disimpan!']);
    }

    public function page_success(){
        return view('pages.dmaic.page_success');
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
