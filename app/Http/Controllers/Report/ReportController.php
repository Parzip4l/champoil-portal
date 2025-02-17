<?php

namespace App\Http\Controllers\report;
use Illuminate\Support\Facades\DB;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


use App\ModelCG\Project;
use App\ModelCG\Schedule;
use App\ModelCG\ScheduleBackup;
use App\Absen;
use App\Employee;
use Carbon\Carbon;
use App\Absen\RequestAbsen;

use App\Backup\AbsenBackup;
use App\User;
use Carbon\CarbonPeriod;
use App\Exports\AttendenceExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Company\CompanyModel;
use Yajra\DataTables\Facades\DataTables;
use App\Organisasi\Organisasi;
use Illuminate\Support\Facades\Log;



class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $code = Auth::user()->employee_code;
        $company = Employee::where('nik', $code)->first();
        $start = date('Y-m-d');
        $end = date('Y-m-d');
        if(!empty($request->input('periode'))){
            $periode = $request->input('periode');
            // Tambahkan satu bulan ke periode
            $periode_min_1_month = date('m', strtotime("-1 month", strtotime($periode)));

            // Ambil bulan dari periode yang sudah ditambahkan satu bulan
            $bulan = date('m', strtotime($periode));

            $start = date('Y').'-'.$periode_min_1_month.'-'.'21';
            $end = date('Y').'-'.$bulan.'-'.'20';

            
        }

        if(!empty($request->input('tanggal'))){
            $tanggal = $request->input('tanggal');
            $explode = explode(' to ',$tanggal);
            $start=$explode[0];
            $end=$explode[1];
           
        }
        $percen_50=0;
        $percen_50_80=0;
        $percen_80_99=0;
        $percen_100=0;
        $project = Project::where('deleted_at',NULL)->where('company',$company->unit_bisnis)->orderBy('name','asc')->get();
        if($project){
            foreach($project as $row){
                $row->persentase_backup=0;
                $row->persentase_absen=0;
                $row->persentase_tanpa_clockout = 0;
                $row->schedule = Schedule::where('project',$row->id)
                                           ->whereBetween('tanggal', [$start, $end])
                                           ->where('shift','!=','OFF')
                                           ->count();

                $row->schedule_backup = ScheduleBackup::where('project',$row->id)->whereBetween('tanggal', [$start, $end])->count();
              

                $row->absen = Absen::where('project', $row->id)
                                    ->whereBetween('tanggal', [$start, $end])
                                    ->count();
                
                $row->tanpa_clockout = Absen::where('project', $row->id)
                                    ->whereBetween('tanggal', [$start, $end])
                                    ->whereNotNull('clock_in')
                                    ->whereNull('clock_out')
                                    ->count();
                $row->need_approval = Schedule::join('requests_attendence','requests_attendence.employee','=','schedules.employee')
                                                    ->where('schedules.project',$row->id)
                                                    ->whereBetween('schedules.tanggal', [$start, $end])
                                                    ->whereBetween('requests_attendence.tanggal', [$start, $end])
                                                    ->where('requests_attendence.aprrove_status','Pending')
                                                    ->count();
                                                    
                $row->approved = Schedule::join('requests_attendence','requests_attendence.employee','=','schedules.employee')
                                            ->where('schedules.project',$row->id)
                                            ->whereBetween('schedules.tanggal', [$start, $end])
                                            ->whereBetween('requests_attendence.tanggal', [$start, $end])
                                            ->where('requests_attendence.aprrove_status','Approved')
                                            ->count();

                // Persentase Tanpa Clockout
                if ($row->absen > 0) {
                    $row->persentase_tanpa_clockout = round(($row->tanpa_clockout / $row->absen) * 100, 2);
                } else {
                    $row->persentase_tanpa_clockout = 0;
                }
            
                if($row->absen > 0 && $row->schedule > 0){
                    $row->persentase_absen = round(($row->absen / $row->schedule) * 100,2);
                    if($row->persentase_absen <= 50 ){
                        $percen_50 +=1;
                    }else if($row->persentase_absen >50 && $row->persentase_absen <= 80 ){
                        $percen_50_80 +=1;
                    }else if($row->persentase_absen >80 && $row->persentase_absen <= 99 ){
                        $percen_80_99 +=1;
                    }else if($row->persentase_absen >= 100 ){
                        $percen_100 +=1;
                    }
                }
                
                $row->absen_backup = Absen::where('project_backup',$row->id)
                                            ->whereBetween('tanggal', [$start, $end])
                                            ->count();    
                if($row->absen_backup > 0){
                    $row->persentase_backup = round(($row->absen_backup / $row->schedule_backup) * 100,2);
                }
            }
        }

        $data['project']=$project;
        $data['percent']=[
            $percen_50,
            $percen_50_80,
            $percen_80_99,
            $percen_100
        ];
        return view('pages.report.absen.index',$data);
    }
    

    public function rekap_report(){
        $project = Project::where('deleted_at',NULL)->where('company','Kas')->get();
        if($project){
            foreach($project as $row){
                $row->persentase_backup=0;
                $row->persentase_absen=0;
                $row->persentase_tanpa_clockout = 0;
                foreach(bulan() as $bln){
                    $month = strtoupper($bln."-".date('Y'));
                    $periode_min_1_month = date('m', strtotime("-1 month", strtotime($bln)));

                    // Ambil bulan dari periode yang sudah ditambahkan satu bulan
                    $bulan = date('m', strtotime($bln));

                    $start = date('Y').'-'.$periode_min_1_month.'-'.'21';
                    $end = date('Y').'-'.$bulan.'-'.'20';

                    $row['schedule'.$bln] = Schedule::where('project',$row->id)
                    ->whereBetween('tanggal', [$start, $end])
                    ->where('shift','!=','OFF')
                    ->count();

                    if(date('m', strtotime("+1 month")) == date('m',strtotime($bln))){
                        $row['on_periode'.$bln]=1;
                    }else{
                        $row['on_periode'.$bln]=0;
                    }
               
                    $row['absen'.$bln] = Absen::where('project', $row->id)
                                        ->whereBetween('tanggal', [$start, $end])
                                        ->count();
                
                    if($row['absen'.$bln] > 0 && $row['schedule'.$bln] > 0){
                        $row['persentase_absen'.$bln] = round(($row['absen'.$bln] / $row['schedule'.$bln]) * 100,2);
                    }
                }
                
              
            }
        }
        $data['project']=$project;
        return view('pages.report.absen.rekap',$data);
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $code = Auth::user()->employee_code;
        $company = Employee::where('nik', $code)->first();
        $project = Project::all();
        $client_id = Auth::user()->project_id;
        $organisasi = Organisasi::where('company', $company->unit_bisnis)->get();
    
        $today = now();
        $periode = $request->input('periode');
        Log::info('Periode dari request:', ['periode' => $periode]);
        if ($periode) {
            // Parsing periode
            [$startDate, $endDate] = explode(' - ', $periode);
            Log::info('Data hasil explode:', ['start' => $startDate, 'end' => $endDate]);
        } else {
            // Periode default
            $startDate = Carbon::create($today->year, $today->month, 21)->format('Y-m-d');
            $endDate = Carbon::create($today->year, $today->month, 20)->addMonth()->format('Y-m-d');
        }

        Log::info('Periode dari default:', ['start' => $startDate]);
        Log::info('Periode dari default:', ['end' => $endDate]);
    
        // Query absensi
        $query = DB::table('users')
            ->join('karyawan', 'karyawan.nik', '=', 'users.name')
            ->leftJoin('absens', function ($join) use ($startDate, $endDate) {
                $join->on('absens.nik', '=', 'users.name')
                    ->whereBetween('absens.tanggal', [$startDate, $endDate]);
            })
            ->select(
                'users.id as user_id',
                'karyawan.nik',
                'karyawan.nama',
                'karyawan.organisasi',
                'karyawan.unit_bisnis',
                DB::raw("GROUP_CONCAT(absens.tanggal ORDER BY absens.tanggal) as dates"),
                DB::raw("GROUP_CONCAT(absens.clock_in ORDER BY absens.tanggal) as clock_ins"),
                DB::raw("GROUP_CONCAT(absens.clock_out ORDER BY absens.tanggal) as clock_outs")
            )
            ->where('karyawan.unit_bisnis', $company->unit_bisnis)
            ->where('karyawan.resign_status', '0')
            ->groupBy('users.id', 'karyawan.nik', 'karyawan.nama', 'karyawan.organisasi', 'karyawan.unit_bisnis')
            ->orderBy('karyawan.nama');
    
        if ($request->organisasi && $request->organisasi !== 'ALL') {
            $query->where('karyawan.organisasi', $request->organisasi);
        }

        if ($request->project && $request->project !== 'ALL') {
            $query->where('absens.project', $request->project);
        }
    
        
    
        if ($request->ajax()) {
            return DataTables::of($query)
                ->addIndexColumn()
                ->editColumn('nama', function ($row) {
                    $link = route('absen.details', ['nik' => $row->nik]);
                    return '<a href="' . $link . '">' . htmlspecialchars($row->nama) . '</a>';
                })
                ->addColumn('attendance', function ($row) use ($startDate, $endDate) {
                    $attendanceData = [];
                    $dates = explode(',', $row->dates);
                    $clockIns = explode(',', $row->clock_ins);
                    $clockOuts = explode(',', $row->clock_outs);
                    $dateIndexMap = array_flip($dates);
    
                    foreach (CarbonPeriod::create($startDate, $endDate) as $date) {
                        $formattedDate = $date->format('Y-m-d');
                        if (isset($dateIndexMap[$formattedDate])) {
                            $index = $dateIndexMap[$formattedDate];
                            $clockIn = $clockIns[$index] ?? '-';
                            $clockOut = $clockOuts[$index] ?? '-';
                        } else {
                            $clockIn = $clockOut = '-';
                        }

                        $sche =  Schedule::where('employee',$row->nik)->where('tanggal',$date->format('Y-m-d'))->first();
                        if(!empty($sche)){
                            $shift = $sche->shift;
                        }else{
                            $shift = "No Schedule";
                        }
                        $attendanceData['absens_' . $date->format('Ymd')] = [
                            'clock_in' => $clockIn,
                            'clock_out' => $clockOut,
                            'employee'=>$row->nama,
                            'schedule'=>$shift
                        ];
                    }
    
                    return $attendanceData;
                })
                ->filter(function ($query) use ($request) {
                    if ($request->has('search') && $request->search['value'] !== '') {
                        $search = strtolower($request->search['value']);
                        $query->where(DB::raw('LOWER(karyawan.nama)'), 'LIKE', "%{$search}%");
                    }
                })
                ->rawColumns(['nama'])
                ->toJson();
        }
    
        // Generate daftar bulan untuk dropdown
        $months = [];
        for ($i = -1; $i < 13; $i++) {
            $start = $today->copy()->startOfYear()->addYear($i >= 12 ? 1 : 0)->addMonths($i % 12)->day(21);
            $end = $start->copy()->addMonth()->day(20);
            $months[$start->format('Y-m-d') . ' - ' . $end->format('Y-m-d')] = $end->format('M Y');
        }
        return view('pages.report.absen.detail',compact('endDate', 'startDate', 'months', 'project', 'client_id', 'organisasi'));
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
