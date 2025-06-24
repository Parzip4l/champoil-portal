<?php

namespace App\Http\Controllers\CgControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\ModelCG\Schedule;
use App\ModelCG\ScheuleParent;
use App\ModelCG\Shift;
use App\Employee;
use App\ModelCG\Project;
use App\ModelCG\ProjectRelations;
use App\ModelCG\ProjectDetails;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ScheduleImport;
use App\Exports\ScheduleExport;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ScheduleControllers extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $currentYear = date('Y');
        $selectedPeriod = $request->input('periode', null);

        $get_data = Schedule::with('project')
            ->select('project', 'periode', DB::raw('count(*) as schedule_count'))
            // ->where('company', Auth::user()->company)
            ->groupBy('project', 'periode')
            ->orderBy(DB::raw("DATE_FORMAT(STR_TO_DATE(periode, '%Y-%m'), '%M-%Y')"), 'ASC'); // Ordering by month in the format MMM-YYYY

        if (Auth::user()->project_id == NULL) {
            if ($selectedPeriod) {
                $get_data->where('periode', $selectedPeriod);
                
            }
            $schedulesByProject = $get_data->get();
        } else {
            $get_data = $get_data->where('project', Auth::user()->project_id);
            if ($selectedPeriod) {
                $get_data->where('periode', $selectedPeriod);
            }
            $schedulesByProject = $get_data->get();
        }
        $jumlahHari=0;
        if ($selectedPeriod) {
            // Pisahkan BULAN dan TAHUN
            [$monthName, $year] = explode('-', strtoupper($selectedPeriod));
        
            // Ubah nama bulan ke angka (contoh: JULY -> 7)
            $monthNumber = date('n', strtotime($monthName));
        
            // Hitung tanggal mulai: 21 bulan sebelumnya
            $start = Carbon::createFromDate($year, $monthNumber, 1)->subMonth()->day(21);
        
            // Hitung tanggal akhir: 20 bulan sekarang
            $end = Carbon::createFromDate($year, $monthNumber, 20);
        
            $jumlahHari = $start->diffInDays($end) + 1; // +1 agar inklusif
    
        }

        if($schedulesByProject){
            foreach($schedulesByProject as $row){
                $row->schedule_count = $row->schedule_count;
                $get_mp = ProjectDetails::where('project_code',$row->project)->get();
                $row->total_mp = 0;
                if($get_mp){
                    foreach($get_mp as $mp){
                        $row->total_mp += $mp->kebutuhan;
                    }
                }
                $row->jumlahHari = $jumlahHari;
                $row->totalSeharusnya = $row->total_mp * $jumlahHari;
            }
        }

        return view('pages.hc.kas.schedule.index', compact('schedulesByProject', 'currentYear', 'selectedPeriod'));
    }



    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $today = now();
        $start_date = $today->day >= 21 ? $today->copy()->day(21) : $today->copy()->subMonth()->day(21);
        $end_date = $today->day >= 21 ? $today->copy()->addMonth()->day(20) : $today->copy()->day(20);

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

        $shift = Shift::all();
        $employeeCode = auth()->user()->employee_code;
        $employee = Employee::where('nik', $employeeCode)->first();
        $unit_bisnis = $employee->unit_bisnis;
        $employee = Employee::where('unit_bisnis', $unit_bisnis)
                            ->whereIn('organisasi',['Frontline Officer','FRONTLINE OFFICER'])
                            ->get();
        $project = Project::all();

        $current_month = $today->format('F');
        $current_year = $today->format('Y'); 

        $year = date('Y'); // Tahun yang diinginkan
        $startDate = Carbon::createFromDate($year, 1, 1); // Tanggal mulai
        $endDate = Carbon::createFromDate($year, 12, 31); // Tanggal akhir

        $period = CarbonPeriod::create($startDate, $endDate); // Membuat periode

        $dates = [];
        foreach ($period as $date) {
            $dates[] = $date->format('Y-m-d'); // Format tanggal sesuai kebutuhan
        }

        

        if($request->input('project_id')){
            $shift = ProjectRelations::where('id_project',$request->input('project_id'))
                                    ->get();
            $data_shift=[];
        }
        $data['employee_proj']=[];
        if($request->input('periode')){
            $data_employee = Employee::where('unit_bisnis', $unit_bisnis)
                                        ->orderBy('nama','asc')
                                        ->whereIn('organisasi',['Frontline Officer','FRONTLINE OFFICER'])
                                        ->get();
            foreach($data_employee as $row){
                $periode = $request->input('periode');
                $periodeDate = strtotime($periode);
                // Subtract one month from the 'periode' date
                $filterDate = strtotime('-1 month', $periodeDate);

                // Format the 'filterDate' to 'mm-YYYY'
                $filter = date('F-Y',$filterDate);
                $cek  = Schedule::where('employee',$row->nik)
                                ->where('periode',$filter)
                                ->where('project',$request->input('project_id'))
                                ->count();
                if($cek > 0){
                    $data['employee_proj'][]=$row;
                }
            }
        }

        if($shift){
            $no=1;
            foreach($shift as $row){
                $detailShift = Shift::where('id',$row->id_shift)->first();
                // foreach($dates as $date){
                    $count = Schedule::where('tanggal',$date)
                                    ->where('shift',@$detailShift->name)
                                    ->count();
                    $data_shift[]=[
                        'id'=>$no,
                        'start'=>$date,
                        'end'=>$date,
                        'code'=>@$detailShift->code,
                        'title'=>@$detailShift->name
                    ];
                // }
                
                $no++;
            }
        }
        return view('pages.hc.kas.schedule.create_mobile', [
            'dates_for_form' => $dates_for_form,
            'employee' => $employee,
            'shift' => $shift,
            'data_shift'=>$data_shift,
            'dates_for_form2' => $dates_for_form,
            'project' => $project,
            'current_month' => $current_month,
            'current_year' => $current_year,
            'employee_proj'=>$data['employee_proj'],
            'filter_project'=>@$request->input('project_id')
        ]);
    }

    function generateRandomCode($length = 6) {
        $characters = '0123456789';
        $code = '';

        for ($i = 0; $i < $length; $i++) {
            $code .= $characters[rand(0, strlen($characters) - 1)];
        }

        return $code;
    }

    // Schedule Import
    public function importSchedule(Request $request)
    {
        try {
            $request->validate([
                'csv_file' => 'required|mimes:xlsx,csv,txt',
            ]);
            $data = $request->file('csv_file');

            $namaFIle = $data->getClientOriginalName();
            $data->move('ScheduleImport', $namaFIle);
            Excel::import(new ScheduleImport, \public_path('/ScheduleImport/'.$namaFIle));

            return redirect()->route('schedule.index')->with('success', 'Import berhasil!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Import gagal. ' . $e->getMessage());
        }
    }

    public function readExcel(Request $request){
        
       
            $request->validate([
                'csv_file' => 'required|mimes:xlsx,csv,txt',
            ]);
            $data = $request->file('csv_file');

            $namaFIle = $data->getClientOriginalName();
            $data->move('ScheduleImport', $namaFIle);


            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load(public_path('ScheduleImport/'.$namaFIle));
            $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
            $periode = date('F-Y');
            $data = [
                'records' => $sheetData,
                'file_name' => $namaFIle,
                'periode'=>$periode
            ];
            return view('pages.hc.kas.schedule.preview',$data);
        
    }

    public function post_data_schedule(Request $request){
        
            $data=$request->all();
            if(!empty($data['schedule_code'])){
                $no=0;
                foreach($data['schedule_code'] as $row){
                    $insert=[
                        "schedule_code"=>$data['schedule_code'][$no],
                        "project"=>$data['project'][$no],
                        "employee"=>$data['employee'][$no],
                        "tanggal"=>$data['tanggal'][$no],
                        "shift"=>$data['shift'][$no],
                        "periode"=>$data['periode'][$no],
                        "created_at"=>date('Y-m-d H:i:s')
                    ];

                    
                    Schedule::insert($insert);
                    $no++;
                }
            }
            // dd($data);
            return redirect()->route('schedule.index')->with('success', 'Import berhasil!');
       
        
    }

    public function exportSchedule() 
    {
        return Excel::download(new ScheduleExport, 'schedule.xlsx');
        return redirect()->back()->with('success', 'Download Berhasil !');
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
            'employee' => 'required',  
            'project' => 'required',
            'tanggal.*' => 'required|date',
            'shift.*' => 'required|string|max:255',
        ]);

        // Ambil data dari formulir
        $employees = $request->input('employee');
        $project = $request->input('project');
        $tanggal = $request->input('tanggal');
        $shifts = $request->input('shift');
        $periodes = $request->input('periode');

        $randomCode = $this->generateRandomCode();

        for ($i = 0; $i < count($tanggal); $i++) {
            $schedule = new Schedule();
            $schedule->schedule_code = $randomCode;
            $schedule->employee = $employees;
            $schedule->project = $project;
            $schedule->tanggal = $tanggal[$i];
            $schedule->shift = $shifts[$i];
            $schedule->periode = $periodes;
            $schedule->save();
        }

        return redirect()->route('schedule.index')->with('success', 'Schedules created successfully.');
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

    public function showDetails($project, $periode)
    {
        $schedules = Schedule::select('employee', 'periode', DB::raw('MIN(id) as schedule_id'))
        ->where('project', $project)
        ->where('periode', $periode)
        ->groupBy('employee', 'periode');
        

        $schedulesWithDetails = Schedule::whereIn('id', $schedules->pluck('schedule_id'))
            ->get();
        foreach($schedulesWithDetails as $row){
            $status = ScheuleParent::where('employee_code',$row->employee)
                                        ->where('project_id', $project)
                                        ->where('periode', $periode)
                                        ->count();
            if($status  == 0){
                $row->status ='<span class="badge rounded-pill bg-success">Active</span>';
            }else{
                $row->status ='<span class="badge rounded-pill bg-danger">Non Active</span>';
            }
        }

        return view('pages.hc.kas.schedule.detailprojectschedule', [
            'project' => $project,
            'periode' => $periode,
            'schedules' => $schedulesWithDetails,
        ]);
    }

    public function showDetailsEmployee($project, $periode, $employee)
    {
        $schedulesWithDetails = Schedule::where('project', $project)
                                ->where('periode', $periode)
                                ->where('employee', $employee)
                                ->get();

        $schedule_code = Schedule::where('project', $project)
                                ->where('periode', $periode)
                                ->where('employee', $employee)
                                ->pluck('schedule_code')
                                ->first();

        $employeeName = Employee::where('nik', $employee)->value('nama');
        $shift = Shift::all();
        return view('pages.hc.kas.schedule.detailsscheduleemployee', [
            'employeeName' => $employeeName,
            'schedules' => $schedulesWithDetails,
            'shift' => $shift,
            'schedule_code' => $schedule_code,
        ]);
    }

    // Edit Manual Schedule

    public function updateMultiple(Request $request)
    {
        // Validasi jika diperlukan
        $request->validate([
            'schedules.*.tanggal' => 'required|date',
            'schedules.*.shift' => 'required',
            // Tambahkan aturan validasi sesuai kebutuhan
        ]);

        // Loop melalui data yang dikirimkan melalui formulir
        foreach ($request->schedules as $scheduleId => $data) {
            // Temukan entitas jadwal berdasarkan ID
            $schedule = Schedule::findOrFail($scheduleId);

            // Update atribut jadwal dengan data yang diterima dari formulir
            $schedule->tanggal = $data['tanggal'];
            $schedule->shift = $data['shift'];
            // Lanjutkan dengan atribut lain yang perlu diperbarui

            // Simpan perubahan
            $schedule->save();
        }

        // Redirect ke halaman terkait atau tampilkan pesan sukses
        return redirect()->back()->with('success', 'Jadwal berhasil diperbarui');
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
        $request->validate([
            // Atur aturan validasi sesuai kebutuhan Anda
        ]);

        // Temukan entitas jadwal berdasarkan ID
        $schedule = Schedule::findOrFail($id);

        // Update atribut jadwal dengan data yang diterima dari formulir
        $schedule->tanggal = $request->tanggal;
        $schedule->shift = $request->shift;
        // Lanjutkan dengan atribut lain yang perlu diperbarui

        // Simpan perubahan
        $schedule->save();

        $project = $schedule->project; 
        $periode = $schedule->periode; 
        $employee = $schedule->employee; 

        // Redirect ke halaman terkait atau tampilkan pesan sukses
        return redirect()->route('schedule.employee', [
            'project' => $project,
            'periode' => $periode,
            'employee' => $employee,
        ])->with('success', 'Jadwal berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($schedule_code)
    {
        $affectedRows = Schedule::where('schedule_code', $schedule_code)->delete();

        if ($affectedRows > 0) {
            return redirect()->route('schedule.index')->with('success', 'Schedule Successfully Deleted');
        } else {
            return redirect()->route('schedule.index')->with('error', 'Schedule Not Found');
        }
    }

    public function stop_report($employee,$periode,$project)
    {
        $expode = explode(",",$employee);
        $isInserted = ScheuleParent::insert([
            'employee_code' => $expode[0],
            'periode' => $expode[1],
            'project_id' => $expode[2],
            'created_at' =>date('Y-m-d')
        ]);
    
        if ($isInserted) {
            return redirect()->route('schedule.index')->with('success', 'Schedule Successfully Added');
        } else {
            return redirect()->route('schedule.index')->with('error', 'Failed to Add Schedule');
        }
    }

    
}
