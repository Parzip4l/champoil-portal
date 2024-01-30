<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Company\CompanyModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Employee;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $company = Auth::user()->unit_bisnis;
        $dataLogin = json_decode(Auth::user()->permission);

        if(in_array('superadmin_access', $dataLogin))
        {
            $companyData = CompanyModel::all();
        } elseif(in_array('admincompany_access', $dataLogin)) {
            $companyData = CompanyModel::where('company_name', $company);
        } else {
            $companyData = CompanyModel::where('company_name', $company);
        }

        return view('pages.company.index',compact('companyData'));
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

    function generateRandomCode($length = 6) {
        $characters = '0123456789';
        $code = '';

        for ($i = 0; $i < $length; $i++) {
            $code .= $characters[rand(0, strlen($characters) - 1)];
        }

        return $code;
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
            'company_name' => 'required|string|max:255',
            'company_address' => 'required|string',
            'latitude' => 'required',
            'longitude' => 'required',
            'radius' => 'required|numeric',
            'logo' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ]);

        try {
            // Proses menyimpan data ke dalam database
            $company = new CompanyModel; // Ganti dengan nama model yang sesuai

            $company->company_code = $this->generateRandomCode();
            $company->company_name = $request->input('company_name');
            $company->company_address = $request->input('company_address');
            $company->use_scedule = $request->input('use_scedule');
            $company->schedule_type = $request->input('schedule_type');
            $company->latitude = $request->input('latitude');
            $company->longitude = $request->input('longitude');
            $company->radius = $request->input('radius');

            // Upload logo
            if ($request->hasFile('logo')) {
                $image = $request->file('logo');
                $filename = time() . '.' . $image->getClientOriginalExtension();
                $destinationPath = public_path('/images/company_logo');
                $image->move($destinationPath, $filename);
                $company->logo = $filename;
            }

            $company->save();

            return redirect()->route('company.index')->with('success', 'Data berhasil disimpan');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
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
        try {
            $company = CompanyModel::findOrFail($id);
            $employeeTotal = Employee::where('unit_bisnis',$company->company_name)->count();
            return view('pages.company.show', compact('company','employeeTotal'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Data tidak ditemukan: ' . $e->getMessage());
        }
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
        try {
            // Validate the form data
            $validatedData = $request->validate([
                'company_name' => 'required|string|max:255',
                'company_address' => 'nullable|string',
                'use_scedule' => 'required|in:Yes,No',
                'schedule_type' => 'required|in:Daily,Monthly,No',
                'latitude' => 'required|string',
                'longitude' => 'required|string',
                'radius' => 'required|numeric',
                'logo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);

            // Handle file upload
            if ($request->hasFile('logo')) {
                $image = $request->file('logo');
                $filename = time() . '.' . $image->getClientOriginalExtension();
                $destinationPath = public_path('/images/company_logo');
                $image->move($destinationPath, $filename);
                $validatedData['logo'] = $filename;
            }

            // Update the company data
            $company = CompanyModel::findOrFail($id);
            $company->update($validatedData);

            // Redirect back with a success message
            return redirect()->route('company.index')->with('success', 'Company updated successfully');
        } catch (\Exception $e) {
            // Handle exceptions, you can log or return an error message
            return redirect()->back()->with('error', 'Error updating company: ' . $e->getMessage())->withErrors($e->getMessage());
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
            $company = CompanyModel::findOrFail($id);
            
            // If ProjectDetails exists, delete it
            if ($company) {
                $company->delete();
            }

            return redirect()->route('company.index')->with('success', 'Company Successfully Deleted');
        } catch (ModelNotFoundException $e) {
            return redirect()->route('company.index')->with('error', 'Project not found');
        } catch (\Exception $e) {
            // Handle other exceptions
            return redirect()->route('company.index')->with('error', 'An error occurred while deleting the project');
        }
    }
}
