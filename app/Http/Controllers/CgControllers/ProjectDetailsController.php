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
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ProjectDetailsImport;
use Illuminate\Support\Facades\Auth;
use App\Employee;

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
            $lembur_rate = $request->input('lembur_rate');

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
            $project->lembur_rate = $lembur_rate;
            $project->save();

            return redirect()->route('project.index')->with(['success' => 'Data Berhasil Disimpan!']);
        } catch (\Illuminate\Validation\ValidationException $e) {
                    // Other exceptions
            return response()->json(['error' => 'An error occurred. Please try again.'], 500);
        }
    }

    public function importExcel(Request $request)
    {
        try {
            $request->validate([
                'csv_file' => 'required|mimes:xlsx,csv,txt',
            ]);
            $data = $request->file('csv_file');

            $namaFIle = $data->getClientOriginalName();
            $data->move('ProjectData', $namaFIle);
            Excel::import(new ProjectDetailsImport, \public_path('/ProjectData/'.$namaFIle));

            return redirect()->route('project.index')->with('success', 'Import berhasil!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Import gagal. ' . $e->getMessage());
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
        $code = Auth::user()->employee_code;
        $company = Employee::where('nik', $code)->first();
        $jabatan = Jabatan::where('parent_category', $company->unit_bisnis)->get();
        $project = Project::find($id);
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
        $projectDetails = ProjectDetails::where('id', $id)->first();
        $jabatan = Jabatan::where('parent_category', 'KAS')->get();
        
        if (!$projectDetails) {
            return abort(404); // Handle jika proyek tidak ditemukan
        }
        return view('pages.hc.kas.project.edit', compact('projectDetails','jabatan'));
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
        try {
            $projectDetails = ProjectDetails::findOrFail($id);
            $projectDetails->update([
                'jabatan' => $request->input('jabatan'),
                'p_gajipokok' => $request->input('p_gajipokok'),
                'p_bpjstk' => $request->input('p_bpjstk'),
                'p_bpjs_ks' => $request->input('p_bpjs_ks'),
                'p_thr' => $request->input('p_thr'),
                'p_tkerja' => $request->input('p_tkerja'),
                'p_tseragam' => $request->input('p_tseragam'),
                'p_tlain' => $request->input('p_tlain'),
                'p_training' => $request->input('p_training'),
                'p_operasional' => $request->input('p_operasional'),
                'p_membership' => $request->input('p_membership'),
                'r_deduction' => $request->input('r_deduction'),
                'p_deduction' => $request->input('p_deduction'),
                'tp_bulanan' => $request->input('tp_bulanan'),
                'lembur_rate' => $request->input('lembur_rate'),
            ]);

            return redirect()->route('project.show',['project' => $projectDetails->project_code])->with('success', 'Data updated successfully');
        } catch (\Exception $e) {
            // Handle the error, you can log it or return an error response
            return back()->withInput()->withErrors(['error' => 'Failed to update data.']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            // Find ProjectDetails by project_code
            $projectDetails = ProjectDetails::findOrFail($id);

            // If ProjectDetails exists, delete it
            if ($projectDetails) {
                $projectDetails->delete();
            }

            return redirect()->route('project.index')->with('success', 'Project Details Successfully Deleted');
        } catch (ModelNotFoundException $e) {
            return redirect()->route('project.index')->with('error', 'Project not found');
        } catch (\Exception $e) {
            // Handle other exceptions
            return redirect()->route('project.index')->with('error', 'An error occurred while deleting the project');
        }
    }
}
