<?php

namespace App\Http\Controllers\Absen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Employee;
use App\Absen\RequestAbsen;
use App\Absen\RequestType;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RequestTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $userId = Auth::id();
        $EmployeeCode = Auth::user()->employee_code;

        $company = Employee::where('nik',$EmployeeCode)->select('unit_bisnis')->first();
        $dataRequest = RequestType::where('company',$company->unit_bisnis)->get();

        return view('pages.company.requestAttendence.index', compact('dataRequest'));
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
        try {
            $userId = Auth::id();
            $EmployeeCode = Auth::user()->employee_code;
            $company = Employee::where('nik', $EmployeeCode)->select('unit_bisnis')->first();
            // Validate the incoming request data
            $validatedData = $request->validate([
                'code' => 'required|string|max:255',
                'name' => 'required|string|max:255',
            ]);

            // Create a new instance of the RequestType model and fill it with the validated data
            $requestType = new RequestType();
            $requestType->code = $validatedData['code'];
            $requestType->name = $validatedData['name'];
            $requestType->company = $company->unit_bisnis;
            // Save the data to the database
            $requestType->save();
            // Redirect to a success page or do something else upon successful data storage
            return redirect()->back()->with('success', 'Data added successfully');
        } catch (\Exception $e) {
            \Log::error($e->getMessage());

            return redirect()->back()->with('error', 'An error occurred while adding data. Please try again.');
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
        //
    }
}
