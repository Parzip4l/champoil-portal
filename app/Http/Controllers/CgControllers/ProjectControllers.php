<?php

namespace App\Http\Controllers\CgControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\ModelCG\Project;
use App\ModelCG\ProjectDetails;
use App\ModelCG\Jabatan;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;

class ProjectControllers extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $project = Project::all();
        return view('pages.hc.kas.project.index', compact('project'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $jabatan = Jabatan::all();
        return view('pages.hc.kas.project.create', compact('jabatan'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    function generateRandomCode($length = 6) {
        $characters = '0123456789';
        $code = '';

        for ($i = 0; $i < $length; $i++) {
            $code .= $characters[rand(0, strlen($characters) - 1)];
        }

        return $code;
    }

    public function store(Request $request)
    {
        try {
            $name = $request->input('name');
            $badan = $request->input('badan');
            $latitude = $request->input('latitude');
            $longtitude = $request->input('longtitude');
            $contract_start = $request->input('contract_start');
            $end_contract = $request->input('end_contract');
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

            $ProjectDetails = [];
            
            foreach($jabatans as $index => $jabatan)
            {
                $ProjectDetails[] = [
                    'jabatan' => $jabatan,
                    'kebutuhan' => $kebutuhan[$index],
                    'p_gajipokok' => $p_gajipokok[$index],
                    'p_bpjstk' => $p_bpjstk[$index],
                    'p_bpjs_ks' => $p_bpjs_ks[$index],
                    'p_thr' => $p_thr[$index],
                    'p_tkerja' => $p_tkerja[$index],
                    'p_tseragam' => $p_tseragam[$index],
                    'p_tlain' => $p_tlain[$index],
                    'p_training' => $p_training[$index],
                    'p_operasional' => $p_operasional[$index],
                    'p_membership' => $p_membership[$index],
                    'r_deduction' => $r_deduction[$index],
                    'p_deduction' => $p_deduction[$index],
                    'tp_gapok' => $tp_gapok[$index],
                    'tp_bpjstk' => $tp_bpjstk[$index],
                    'tp_bpjsks' => $tp_bpjsks[$index],
                    'tp_thr' => $tp_thr[$index],
                    'tp_tunjangankerja' => $tp_tunjangankerja[$index],
                    'tp_tunjanganseragam' => $tp_tunjanganseragam[$index],
                    'tp_tunjanganlainnya' => $tp_tunjanganlainnya[$index],
                    'tp_training' => $tp_training[$index],
                    'tp_operasional' => $tp_operasional[$index],
                    'tp_ppn' => $tp_ppn[$index],
                    'tp_pph' => $tp_pph[$index],
                    'tp_cashin' => $tp_cashin[$index],
                    'tp_total' => $tp_total[$index],
                    'tp_membership' => $tp_membership[$index],
                    'tp_bulanan' => $tp_bulanan[$index],
                    'rate_harian' => $rate_harian[$index]
                ];
            }
            $randomCode = $this->generateRandomCode();

            $project = new Project();
            $project->id = $randomCode;
            $project->name = $name;
            $project->badan = $badan;
            $project->latitude = $latitude;
            $project->longtitude = $longtitude;
            $project->contract_start = $contract_start;
            $project->end_contract = $end_contract;
            $project->save();

            return redirect()->route('project.index')->with(['success' => 'Data Berhasil Disimpan!']);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Validation failed
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
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
        // Mengambil data proyek berdasarkan project_code
        $project = Project::where('id', $id)->first();  // Ubah sesuai dengan model dan kolom yang benar

        if (!$project) {
            return abort(404); // Handle jika proyek tidak ditemukan
        }

        // Mengambil detail proyek berdasarkan project_code
        $projectDetails = ProjectDetails::where('project_code', $project->id)->get();

        return view('pages.hc.kas.project.details', compact('project', 'projectDetails'));
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
