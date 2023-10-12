<?php

namespace App\Http\Controllers\CgControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\ModelCG\Payroll;
use App\Employee;
use App\Absen;
use Carbon\Carbon;

class PayrolNS extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $dataPayroll = Payroll::all();

        return view('pages.hc.kas.payroll.index',compact('dataPayroll'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $today = now();
        $start_date = $today->day >= 21 ? $today->copy()->day(21) : $today->copy()->subMonth()->day(21);
        $end_date = $today->day >= 21 ? $today->copy()->addMonth()->day(20) : $today->copy()->day(20);

        $start_date2 = Carbon::parse($start_date)->format('d-m-Y');
        $end_date2 = Carbon::parse($end_date)->format('d-m-Y');

        $date_range = [];
        $current_date = $start_date->copy();

        while ($current_date->lte($end_date)) {
            $date_range[] = $current_date->copy();
            $current_date->addDay();
        }

        $dates_for_form = [];
        foreach ($date_range as $date) {
            $dates_for_form[$date->toDateString()] = $date->format('d F Y');
        }

        $employee = Employee::all();
        return view('pages.hc.kas.payroll.create', compact('employee','start_date2','end_date2'));
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

    public function getEmployees(Request $request) {
        $unitBisnis = $request->input('unit_bisnis');
    
        // Ambil daftar karyawan berdasarkan unit bisnis
        $employees = Employee::where('unit_bisnis', $unitBisnis)->get();
    
        return response()->json(['employees' => $employees]);
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
