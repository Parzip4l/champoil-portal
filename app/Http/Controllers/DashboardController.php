<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Purchase;
use App\Sales;
use App\Invoice;
use Carbon\Carbon;
use App\Absen;
use App\Employee;
use App\Feedback;
use App\Payrol;
use App\Payrollns;
use App\Payrolinfo\Payrolinfo;
use App\ModelCG\Payroll;
use App\Absen\RequestAbsen;
use App\ModelCG\asign_test;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Mail\PayslipEmail;
use Illuminate\Support\Facades\Mail;
use App\PengajuanSchedule\PengajuanSchedule;
use PDF;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Pengumuman\Pengumuman;
Use App\Organisasi\Organisasi;
use App\News\News;
use App\Company\CompanySetupChecklist;
use App\Company\CompanyModel;

// Task
use App\TaskManagement\TaskMaster;
use App\TaskManagement\Subtask;
use App\TaskManagement\TaskUser;
use App\TaskManagement\TaskComment;

// config

use Illuminate\Support\Facades\Config;

class DashboardController extends Controller
{
    public function index()
    {   
        $code = Auth::user()->employee_code;
        $company = Employee::where('nik', $code)->first();
        $companyId = CompanyModel::where('company_name', $company->unit_bisnis)->value('company_code');

        // Greeting
        $today = now();
        $hour = $today->hour;
        $hariini2 = Carbon::now();

        if ($hour >= 1 && $hour < 11) {
            $greeting = 'Selamat Pagi';
        } elseif ($hour >= 11 && $hour < 16) {
            $greeting = 'Selamat Siang';
        } elseif ($hour >= 16 && $hour < 20) {
            $greeting = 'Selamat Sore';
        } else {
            $greeting = 'Selamat Malam';
        }
        // Birthday Employee
        $birthdays = Employee::where('unit_bisnis', $company->unit_bisnis)
                        ->where('resign_status', 0)
                        ->select('tanggal_lahir','nama','gambar')
                        ->get();
                     
        $upcomingBirthdays = $birthdays->filter(function ($employee) use ($today) {
            $birthDate = Carbon::parse($employee->tanggal_lahir)->setYear($today->year);
            $employee->usia = Carbon::parse($employee->tanggal_lahir)->age;
            return $birthDate->isToday() || ($birthDate->isAfter($today) && $birthDate->diffInDays($today) <= 7);
        });
        // Endbirthday
        $startDate = $today->day >= 21 ? $today->copy()->day(20) : $today->copy()->subMonth()->day(21);
        $endDate = $today->day >= 21 ? $today->copy()->addMonth()->day(20) : $today->copy()->day(20);
        $dataRequest = RequestAbsen::join('karyawan', 'requests_attendence.employee', '=', 'karyawan.nik')
            ->where('karyawan.unit_bisnis', $company->unit_bisnis)
            ->where('aprrove_status', 'Pending')
            ->select('requests_attendence.*', 'karyawan.*')
            ->get();
        
        $pengajuanSchedule = PengajuanSchedule::where('status', 'Ditinjau')->get();

        // Absensi Statistik
        $dataAbsen = Absen::join('karyawan', 'absens.nik', '=', 'karyawan.nik')
                    ->where('karyawan.unit_bisnis', $company->unit_bisnis)
                    ->whereBetween('tanggal', [$startDate, $endDate])
                    ->select('absens.*', 'karyawan.*')
                    ->get();

        $DataHadir = Absen::join('karyawan', 'absens.nik', '=', 'karyawan.nik')
                    ->where('karyawan.unit_bisnis', $company->unit_bisnis)
                    ->whereBetween('tanggal', [$startDate, $endDate])
                    ->where('status', 'H')
                    ->select('absens.*', 'karyawan.*')
                    ->get();

        // Data Absensi 
        $labels = [];
        $currentDate = $startDate->copy();
        while ($currentDate->lte($endDate)) {
            $labels[] = $currentDate->format('d M'); // Ubah format tanggal ke 'D d M'
            $currentDate->addDay();
        }

        // Calculate the total absences for each day
        $dataAbsenByDay = [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Total Absensi',
                    'backgroundColor' => '#424874',
                    'data' => [],
                ]
            ]
        ];

        foreach ($labels as $label) {
            // Konversi kembali ke format 'Y-m-d' untuk pencarian data di $DataHadir
            $dateForDatabase = date('Y-m-d', strtotime($label));
            $absencesCount = $DataHadir->where('tanggal', $dateForDatabase)->count();
            $dataAbsenByDay['datasets'][0]['data'][] = $absencesCount;
        }

        // Persantase Hadir, Sakit, Izin, WFE
        $dataKehadirantotal = $dataAbsen->where('status', 'H')
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->count();

        $dataSakit = $dataAbsen->where('status', 'Sakit')
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->count();

        $dataIzin = $dataAbsen->where('status', 'I')
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->count();
        
        $wfe = $dataAbsen->where('status', 'WFE')
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->count();
        
        $vc = $dataAbsen->where('status', 'VC')
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->count();
        
        $DataTotalKehadiran = [
            'labels' => ['Hadir', 'Sakit', 'Izin', 'Wfe','Visit Customer'],
            'datasets' => [
                [
                    'label' => 'Total Data',
                    'backgroundColor' => ['#277BC0', '#FFB200', '#FFCB42', '#66d1d1', '#f36'],
                    'data' => [$dataKehadirantotal, $dataSakit, $dataIzin,$wfe,$vc],
                ]
            ]
        ];

        $slack_on = Employee::where('unit_bisnis', $company->unit_bisnis)
                            ->join('users','users.name','=','karyawan.nik')
                            ->where('resign_status', 0)
                            ->whereNull('users.project_id')
                            ->whereNotNull('slack_id')
                            ->where('organisasi','FRONTLINE OFFICER')
                            ->count();

        $slack_off = Employee::where('unit_bisnis', $company->unit_bisnis)
                            ->join('users','users.name','=','karyawan.nik')
                            ->where('resign_status', 0)
                            ->whereNull('users.project_id')
                            ->where('organisasi','FRONTLINE OFFICER')
                            ->whereNull('slack_id')
                            ->count();
                            
        $data_karyawan = Employee::where('unit_bisnis', $company->unit_bisnis)
                            ->join('users','users.name','=','karyawan.nik')
                            ->where('resign_status', 0)
                            ->whereNull('users.project_id')
                            ->where('users.company',$company->unit_bisnis)
                            ->where('organisasi','FRONTLINE OFFICER')
                            ->get();
        $bpjs  = 0;
        $no_bpjs  = 0;
        if($data_karyawan){
            foreach($data_karyawan as $row){
                $cek =  Payrolinfo::whereIn('bpjs_tk',[0,NULL,'-'])->where('employee_code',$row->nik)->count();
                if($cek  > 0){
                    $bpjs +=1;
                }else{
                    $no_bpjs +=1;
                }
            }
        }

        $data_bpjs=[
            "bpjs"=>$bpjs,
            "no_bpjs"=>$no_bpjs
        ];


        $UserSlack = [
            'labels' => ['Slack Complete','Slack  Not Complete'],
            'datasets' => [
                [
                    'label' => 'Total Data',
                    'backgroundColor' => ['#277BC0', '#FFB200'],
                    'data' => [$slack_on, $slack_off],
                ]
            ]
        ];
        //End Absensi Statistik
        $organisasi = Organisasi::where('company', $company->unit_bisnis)->get();
        $organisasiUser = $company->organisasi;
        // Pengumuman 
        $tanggal_sekarang = now()->format('Y-m-d');
        $pengumuman = Pengumuman::where('company',$company->unit_bisnis)->where('end_date', '>=', $tanggal_sekarang)
                        ->where(function ($query) use ($organisasiUser) {
                            $query->where('tujuan', $organisasiUser)
                                  ->orWhere('tujuan', 'semua');
                        })
                        ->get();

        // News
        $news = News::where('company', $company->unit_bisnis)->get();
        
        // Employee Statistik
        $dataChartKaryawan = Employee::where('unit_bisnis', $company->unit_bisnis)
        ->where('resign_status',0)
        ->get();

        $dataChartKaryawanCurrent = Employee::whereYear('created_at', now()->year)
        ->where('unit_bisnis', $company->unit_bisnis)
        ->where('resign_status',0)
        ->whereMonth('created_at', now()->month)
        ->get();

        // Ambil data karyawan bulan sebelumnya
        $dataChartKaryawanPrevious = Employee::whereYear('created_at', now()->subMonth()->year)
        ->where('unit_bisnis', $company->unit_bisnis)
        ->where('resign_status',0)
        ->whereMonth('created_at', now()->subMonth()->month)
        ->get();

        // Hitung jumlah karyawan bulan ini
        $DataFrontlineCurrent = $dataChartKaryawanCurrent->where('organisasi', 'Frontline Officer')->count();
        $DataManagementCurrent = $dataChartKaryawanCurrent->where('organisasi', 'Management Leaders')->count();
        $DataAllKaryawanCurrent = $dataChartKaryawanCurrent->count();

        // Hitung jumlah karyawan bulan sebelumnya
        $DataFrontlinePrevious = $dataChartKaryawanPrevious->where('organisasi', 'Frontline Officer')->count();
        $DataManagementPrevious = $dataChartKaryawanPrevious->where('organisasi', 'Management Leaders')->count();
        $DataAllKaryawanPrevious = $dataChartKaryawanPrevious->count();

        $percentageChangeFrontline = $DataFrontlinePrevious > 0 ? (($DataFrontlineCurrent - $DataFrontlinePrevious) / $DataFrontlinePrevious * 100) : 0;
        $percentageChangeManagement = $DataManagementPrevious > 0 ? (($DataManagementCurrent - $DataManagementPrevious) / $DataManagementPrevious * 100) : 0;
        $percentageChangeAll = $DataAllKaryawanPrevious > 0 ? (($DataAllKaryawanCurrent - $DataAllKaryawanPrevious) / $DataAllKaryawanPrevious * 100) : 0;

        if($company->unit_bisnis === 'CHAMPOIL')
        {
            $DataFrontline = $dataChartKaryawan->where('organisasi','Frontline Officer')->count();
            $DataManagement = $dataChartKaryawan->where('organisasi','Management Leaders')->count();
            $DataAllKaryawan = $dataChartKaryawan->count();
        }else{
            $DataFrontline = $dataChartKaryawan->where('organisasi','FRONTLINE OFFICER')->count();
            $DataManagement = $dataChartKaryawan->where('organisasi','MANAGEMENT LEADERS')->count();
            $DataAllKaryawan = $dataChartKaryawan->count(); 
        }
        

        $ChartKaryawan = [
            'labels' => ['All', 'Management Leaders', 'Frontline Officer'],
            'datasets' => [
                [
                    'label' => '',
                    'backgroundColor' => ['#277BC0', '#FFB200', '#66d1d1'],
                    'data' => [$DataAllKaryawan, $DataManagement, $DataFrontline],
                ]
            ]
        ];

        $today = now()->format('Y-m-d');  // Tanggal hari ini

        $kontrakKaryawan = Employee::where('unit_bisnis',$company->unit_bisnis)
            ->where('resign_status', 0)
            ->where('berakhirkontrak', '>', $today)
            ->where('berakhirkontrak', '<=', now()->addMonth()->format('Y-m-d'))
            ->select('nama','berakhirkontrak', 'gambar')
            ->get();

        foreach ($kontrakKaryawan as $employee) {
                $contractEndDate = $employee->berakhirkontrak;
                $remainingDays = now()->diffInDays($contractEndDate, false);
            }

        $karyawanTidakAbsenHariIni = DB::table('karyawan')
            ->leftJoin('absens', function ($join) use ($today) {
                $join->on('karyawan.nik', '=', 'absens.nik')
                    ->whereDate('absens.tanggal', $today);
            })
            ->where('unit_bisnis',$company->unit_bisnis)
            ->where('resign_status',0)
            ->whereNull('absens.nik')
            ->select('karyawan.nama','karyawan.organisasi', 'karyawan.gambar')
            ->paginate(7);
        // End Employee Statistik



        // Absen Data
        if (Auth::check()) {
            // Get the authenticated user
            $user = Auth::user();
        
            if ($user->employee_code) {
                // Get all Karyawan data
                $karyawan = Employee::all();
        
                // Get the last Absensi record for the user
                $lastAbsensi = $user->Absen()->latest()->first();
        
                // Get the authenticated user's ID and today's date
                $userId = Auth::id();
                $EmployeeCode = Auth::user()->employee_code;
                $hariini = now()->format('Y-m-d');
                
                // Get Karyawan data for the authenticated user
                $datakaryawan = Employee::join('users', 'karyawan.nik', '=', 'users.employee_code')
                    ->where('users.employee_code', $userId)
                    ->select('karyawan.*')
                    ->get();
        
                // Get log of Absensi for the authenticated user on the current date
                $logs = Absen::where('nik', $EmployeeCode)
                    ->whereDate('tanggal', $hariini)
                    ->get();
        
                // Check if the user has already clocked in or out for the day
                $alreadyClockIn = false;
                $alreadyClockOut = false;
                $isSameDay = false;
        
                if ($lastAbsensi) {
                    if ($lastAbsensi->clock_in && !$lastAbsensi->clock_out) {
                        $alreadyClockIn = true;
                    } elseif ($lastAbsensi->clock_in && $lastAbsensi->clock_out) {
                        $alreadyClockOut = true;
                        $lastClockOut = Carbon::parse($lastAbsensi->clock_out);
                        $today = Carbon::today();
                        $isSameDay = $lastClockOut->isSameDay($today);
                    }
                }
            }
        }

        $asign_test = asign_test::where('employee_code',Auth::user()->employee_code)->where('status',0)->get();

        // Data Statistik Payroll
        if ($company->unit_bisnis === 'Kas') {
            $frontlineSalaries = Payroll::all();
            $managementSalaries = Payrol::where('unit_bisnis', $company->unit_bisnis)->get();
        } else {
            $managementSalaries = Payrol::where('unit_bisnis', $company->unit_bisnis)->get();
            $frontlineSalaries = Payrollns::all();
        }

        $managementData = $managementSalaries->groupBy(function ($salary) {
            return $salary->created_at->format('M Y');
        })->map->sum('net_salary');

        $frontlineData = $frontlineSalaries->groupBy(function ($salary) {
            return $salary->created_at->format('M Y');
        })->map->sum('thp');

        $managementData2 = $managementSalaries->groupBy(function ($salary) {
            return $salary->created_at->format('Y');
        })->map->sum('net_salary');
        
        $frontlineData2 = $frontlineSalaries->groupBy(function ($salary) {
            return $salary->created_at->format('Y');
        })->map->sum('thp');
        
        $lastPeriod = $managementData->keys()->last();
        $lastValue = $managementData->last();

        $lastPeriodF = $frontlineData->keys()->last();
        $lastValueF = $frontlineData->last();

        $previousPeriod = $managementData->keys()->slice(-2, 1)->first();
        $previousValue = $managementData->get($previousPeriod);

        $previousPeriodF = $frontlineData->keys()->slice(-2, 1)->first();
        $previousValueF = $frontlineData->get($previousPeriodF);

        $totalValue = $lastValueF + $lastValue;
        $previusValue = $previousValue + $previousValueF;
        if ($previusValue == 0) {
            $percentageChange = 0; // Or any default value you wish to use
        } else {
            $percentageChange = (($totalValue - $previusValue) / $previusValue) * 100;
        }
        // End Payrol Statistik
        

        // Task Management
        $currentTaskData = TaskMaster::where('company', $company->unit_bisnis)
                                        ->get();

        // Calculate totals for current period
        $totalTasks = $currentTaskData->count();
        $completedTasks = $currentTaskData->where('status', 'Completed')->count();
        $inProgressTasks = $currentTaskData->where('status', 'In Progress')->count();
        $overdueTasks = $currentTaskData->filter(function ($task) {
            return Carbon::parse($task->due_date)->isPast() && $task->status !== 'Completed';
        })->count();

        $TaskOnprogress = TaskMaster::where('company', $company->unit_bisnis)
                                        ->get();

        foreach ($TaskOnprogress as $task) {
            // Get assigned users for the task
            $task->assignedUsers = TaskUser::where('task_id', $task->id)
                                            ->join('karyawan', 'task_user.nik', '=', 'karyawan.nik')
                                            ->get(['karyawan.nama', 'karyawan.gambar', 'karyawan.nik']);
        }

        // Checlist New Company

        $labels = Config::get('company_checklist');
        $steps = CompanySetupChecklist::where('company_code', $companyId)->get()->keyBy('key');

        $total = count($labels);
        $done = collect($labels)->keys()->filter(function ($k) use ($steps) {
            return isset($steps[$k]) && $steps[$k]->is_completed;
        })->count();

        $progress = $total > 0 ? round(($done / $total) * 100) : 0;

        $compactVariables = [
            'karyawan', 'alreadyClockIn', 'alreadyClockOut', 'isSameDay', 'datakaryawan', 'logs', 'hariini',
            'asign_test', 'dataRequest', 'pengajuanSchedule', 'dataAbsenByDay', 'DataTotalKehadiran',
            'ChartKaryawan', 'kontrakKaryawan', 'karyawanTidakAbsenHariIni', 'managementData', 'frontlineData',
            'managementData2', 'frontlineData2', 'pengumuman', 'news', 'upcomingBirthdays', 'greeting', 'hariini2',
            'DataManagement', 'DataFrontline', 'totalValue', 'percentageChange', 'percentageChangeManagement','percentageChangeFrontline',
            'percentageChangeAll','totalTasks','completedTasks','inProgressTasks','overdueTasks','TaskOnprogress','UserSlack','data_bpjs','labels', 'steps', 'progress'
        ];
        
        return view('dashboard', compact(...$compactVariables));
    }

    public function StoreFeedback(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required'
        ]);
        
        $coa = new Feedback();
        $coa->name = $request->input('name');
        $coa->email = $request->input('email');
        $coa->rating = $request->input('rating');
        $coa->feedback = $request->input('feedback');
        $coa->save();

        return redirect()->back()->with('success', 'Thankyou For Feedback');
    }

    public function sendEmail($id)
    {
        // Retrieve data based on the ID
        $data = Payrol::findOrFail($id);
        $dataPayslip = Payrol::where('id', $id)->get();

        $pdf = PDF::loadView('pages.hc.payrol.pdfslip',compact('dataPayslip'))->setOptions(['defaultFont' => 'sans-serif']);

        // Simpan PDF ke file sementara
        $pdfPath = storage_path('app/public/payslip.pdf');
        $pdf->save($pdfPath);

        // Kirim email dengan lampiran PDF
        $data = [
            'subject' => 'Slip Gaji',
            'body' => 'Terlampir adalah slip gaji Anda',
            'attachmentName' => 'slip_gaji.pdf',
        ];

        // Send email
        Mail::to('sobirin@champoil.co.id')->send(new PayslipEmail($dataPayslip,$pdfPath));
        // Hapus file PDF sementara
        unlink($pdfPath);

        return redirect()->back()->with('success', 'Email sent successfully!');
    }

    public function kirimEmail()
    {
        $userEmail = 'sobirin@champoil.co.id';

        Mail::to($userEmail)->send(new PayslipEmail());

        return redirect()->back()->with('success', 'Email Has Been Send');
    }

    public function toggle(Request $request)
    {
        $request->validate([
            'key' => 'required|string',
        ]);

        $key = $request->input('key');
        $checklistLabels = Config::get('company_checklist');

        if (!array_key_exists($key, $checklistLabels)) {
            return response()->json(['success' => false, 'message' => 'Invalid checklist key.'], 400);
        }

        // Ambil company ID dari NIK user
        $code = Auth::user()->employee_code;
        $company = Employee::where('nik', $code)->first();
        $companyId = CompanyModel::where('company_name', $company->unit_bisnis)->value('company_code');

        // Toggle checklist
        $step = CompanySetupChecklist::firstOrNew([
            'company_code' => $companyId,
            'key' => $key,
        ]);

        $step->is_completed = !$step->is_completed ?? false;
        $step->save();

        // Ambil semua step
        $steps = CompanySetupChecklist::where('company_code', $companyId)->get()->keyBy('key');

        // Hitung progress
        $total = count($checklistLabels);
        $done = collect($checklistLabels)->keys()->filter(function ($k) use ($steps) {
            return isset($steps[$k]) && $steps[$k]->is_completed;
        })->count();

        $progress = $total > 0 ? round(($done / $total) * 100) : 0;

        return response()->json([
            'success' => true,
            'progress' => $progress,
            'steps' => $steps->map(function ($step) {
                return [
                    'key' => $step->key,
                    'is_completed' => $step->is_completed,
                ];
            })->values()
        ]);
    }
}
