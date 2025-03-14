<?php

namespace App\Http\Controllers\CgControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\ModelCG\Project;
use App\ModelCG\Shift;
use App\ModelCG\ProjectRelations;
use App\ModelCG\ProjectDetails;
use App\ModelCG\Jabatan;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Employee;


class ProjectControllers extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $code = Auth::user()->employee_code;
        $company = Employee::where('nik', $code)->first();
        
        $project = DB::connection('mysql_secondary')
                        ->table('projects')
                        ->where('company', $company->unit_bisnis)
                        ->get();
        
        return view('pages.hc.kas.project.index', compact('project'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $code = Auth::user()->employee_code;
        $company = Employee::where('nik', $code)->first();

        $jabatan = Jabatan::where('parent_category',$company->unit_bisnis)->get();
        
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
            $code = Auth::user()->employee_code;
            $company = Employee::where('nik', $code)->first();

            $name = $request->input('name');
            $badan = $request->input('badan');
            $latitude = $request->input('latitude');
            $longtitude = $request->input('longtitude');
            $contract_start = $request->input('contract_start');
            $end_contract = $request->input('end_contract');
            $tanggal_deploy = $request->input('tanggal_deploy');
        
            $randomCode = $this->generateRandomCode();

            // dd($company->unit_bisnis);

            $project = new Project();
            $project->id = $randomCode;
            $project->name = $name;
            $project->badan = $badan;
            $project->latitude = $latitude;
            $project->longtitude = $longtitude;
            $project->contract_start = $contract_start;
            $project->end_contract = $end_contract;
            $project->company = $company->unit_bisnis;
            $project->tanggal_deploy = $tanggal_deploy;
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
        $employee = Auth::user();
        // Mengambil data proyek berdasarkan project_code
        $data['project'] = Project::where('id', $id)->first();  // Ubah sesuai dengan model dan kolom yang benar
        $data['atasan'] = Employee::where('unit_bisnis',$employee->company)->where('organisasi','FRONTLINE OFFICER')->where('resign_status',0)->get();
        
        if (!$data['project']) {
            return abort(404); // Handle jika proyek tidak ditemukan
        }

        // Mengambil detail proyek berdasarkan project_code
        $data['projectDetails'] = ProjectDetails::where('project_code', $data['project']->id)->get();
        $data['shift'] = Shift::all();
        if($data['shift']){
            foreach($data['shift'] as $row){
                $row->count = ProjectRelations::where('id_project',$id)
                                                ->where('id_shift',$row->id)
                                                ->count();
            }
        }

        return view('pages.hc.kas.project.details',$data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $project = Project::where('id', $id)->first();  // Ubah sesuai dengan model dan kolom yang benar

        if (!$project) {
            return abort(404); // Handle jika proyek tidak ditemukan
        }

        $data['projectDetails'] = ProjectDetails::where('project_code', $project->id)->get();
        $data['shift'] = Shift::all();
        return view('pages.hc.kas.project.edit',$data);
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

        // dd($request->input('leader_pic'));
        try {
            $Project = Project::findOrFail($id);
            $Project->update([
                'name' => $request->input('name'),
                'latitude' => $request->input('latitude'),
                'longtitude' => $request->input('longtitude'),
                'contract_start' => $request->input('contract_start'),
                'end_contract' => $request->input('end_contract'),
                'badan' => $request->input('badan'),
                'leader_pic' => $request->input('leader_pic'),
            ]);

            $cek = ProjectRelations::where('id_project', $id)->delete();
            $data = $request->all();
            if($data['shift']){
                foreach($data['shift'] as $row){
                    $insert=[
                        "id_project"=>$id,
                        "id_shift"=>$row
                    ];
                    ProjectRelations::insert($insert);
                }
            }
        
            return redirect()->back()->with('success', 'Data updated successfully');
        } catch (\Exception $e) {
            // Handle the error, you can log it or return an error response
            return back()->withErrors(['error' => 'Failed to update data.']);
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
            $project = Project::findOrFail($id);
            
            // Find ProjectDetails by project_code
            $projectDetails = ProjectDetails::where('project_code', $id)->first();

            // If ProjectDetails exists, delete it
            if ($projectDetails) {
                $projectDetails->delete();
            }

            // Delete the project
            $project->delete();

            return redirect()->route('project.index')->with('success', 'Project Successfully Deleted');
        } catch (ModelNotFoundException $e) {
            return redirect()->route('project.index')->with('error', 'Project not found');
        } catch (\Exception $e) {
            // Handle other exceptions
            return redirect()->route('project.index')->with('error', 'An error occurred while deleting the project');
        }
    }
}
