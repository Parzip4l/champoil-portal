<?php

namespace App\Http\Controllers\CgControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\ModelCG\Project;
use App\ModelCG\Jabatan;
use App\ModelCG\ProjectDetails;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;

class ProjectDetailsController extends Controller
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
    public function createDetails($id)
{
    $project = Project::find($id);
    $jabatan = Jabatan::all();
    return view('pages.hc.kas.project.createdetails', compact('project', 'jabatan'));
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
            $project_code = $request->input('project_code');
            $jabatans = $request->input('jabatan');
            $kebutuhan = $request->input('kebutuhan');
            $p_gajipokok = $request->input('p_gajipokok');
            $p_bpjstk = $request->input('p_bpjstk');
            $p_bpjs_ks = $request->input('p_bpjs_ks');
            $p_thr = $request->input('p_thr');
            $p_tkerja = $request->input('p_tkerja');
            $p_tseragam = $request->input('p_tseragam');
            $p_tlain = $request->input('p_tlain');
            $p_training = $request->input('p_training');
            $p_operasional = $request->input('p_operasional');
            $p_membership = $request->input('p_membership');
            $r_deduction = $request->input('r_deduction');
            $p_deduction = $request->input('p_deduction');
            $tp_gapok = $request->input('tp_gapok');
            $tp_bpjstk = $request->input('tp_bpjstk');
            $tp_bpjsks = $request->input('tp_bpjsks');
            $tp_thr = $request->input('tp_thr');
            $tp_tunjangankerja = $request->input('tp_tunjangankerja');
            $tp_tunjanganseragam = $request->input('tp_tunjanganseragam');
            $tp_tunjanganlainnya = $request->input('tp_tunjanganlainnya');
            $tp_training = $request->input('tp_training');
            $tp_operasional = $request->input('tp_operasional');
            $tp_ppn = $request->input('tp_ppn');
            $tp_pph = $request->input('tp_pph');
            $tp_cashin = $request->input('tp_cashin');
            $kebutuhan = $request->input('kebutuhan');
            $tp_total = $request->input('tp_total');
            $tp_membership = $request->input('tp_membership');
            $tp_bulanan = $request->input('tp_bulanan');
            $rate_harian = $request->input('rate_harian');

            $project = new ProjectDetails();
            $project->project_code = $project_code;
            $project->jabatan = $jabatans;
            $project->kebutuhan = $kebutuhan;
            $project->p_gajipokok = $p_gajipokok;
            $project->p_bpjstk = $p_bpjstk;
            $project->p_bpjs_ks = $p_bpjs_ks;
            $project->p_thr = $p_thr;
            $project->p_tkerja = $p_tkerja;
            $project->p_tseragam = $p_tseragam;
            $project->p_tlain = $p_tlain;
            $project->p_training = $p_training;
            $project->p_operasional = $p_operasional;
            $project->p_membership = $p_membership;
            $project->r_deduction = $r_deduction;
            $project->p_deduction = $p_deduction;
            $project->tp_gapok = $tp_gapok;
            $project->tp_bpjstk = $tp_bpjstk;
            $project->tp_bpjsks = $tp_bpjsks;
            $project->tp_thr = $tp_thr;
            $project->tp_tunjangankerja = $tp_tunjangankerja;
            $project->tp_tunjanganseragam = $tp_tunjanganseragam;
            $project->tp_tunjanganlainnya = $tp_tunjanganlainnya;
            $project->tp_training = $tp_training;
            $project->tp_operasional = $tp_operasional;
            $project->tp_ppn = $tp_ppn;
            $project->tp_pph = $tp_pph;
            $project->tp_cashin = $tp_cashin;
            $project->tp_total = $tp_total;
            $project->tp_membership = $tp_membership;
            $project->tp_bulanan = $tp_bulanan;
            $project->rate_harian = $rate_harian;
            $project->save();

            return redirect()->route('project.index')->with(['success' => 'Data Berhasil Disimpan!']);
        } catch (\Illuminate\Validation\ValidationException $e) {
                    // Other exceptions
            return response()->json(['error' => 'An error occurred. Please try again.'], 500);
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
        $project = Project::find($id);
        $jabatan = Jabatan::all();
        return view('pages.hc.kas.project.createdetails', compact('project', 'jabatan'));
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
