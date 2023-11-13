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
