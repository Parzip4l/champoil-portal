<?php

namespace App\Http\Controllers\Absen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Absen;
use App\Employee;
use App\ModelCG\Schedule;
use App\ModelCG\ScheduleBackup;
use App\ModelCG\Project;
use App\User;
use App\Absen\RequestAbsen;
use Carbon\Carbon;
use App\Absen\RequestType;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RequestControllers extends Controller
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
        $company = Employee::where('nik', $EmployeeCode)->first();
        
        if($company->organisasi == 'Frontline Officer' || $company->organisasi =='FRONTLINE OFFICER'){
            $get_project = Schedule::where('employee',$EmployeeCode)->first();
            $request_absen = RequestAbsen::join('karyawan', 'karyawan.nik', '=', 'requests_attendence.employee')
                                        ->where('karyawan.unit_bisnis', $company->unit_bisnis)
                                        ->whereDate('requests_attendence.created_at','>','2024-06-20')
                                        ->where('requests_attendence.aprrove_status','Pending')
                                        ->select('requests_attendence.*')
                                        ->orderBy('requests_attendence.tanggal', 'desc')
                                        ->limit(500)
                                        ->get();
            $dataRequest=[];
            if($request_absen){
                foreach($request_absen as $row){
                    $cek = Schedule::whereDate('schedules.tanggal','>','2024-06-20')
                            ->where('project',$get_project->project)
                            ->where('employee',$row->employee)
                            ->count();
                    if($cek > 0){
                        $dataRequest[]=$row;
                    }
                }
                
            }
        }else{
            $dataRequest = RequestAbsen::join('karyawan', 'karyawan.nik', '=', 'requests_attendence.employee')
                               ->where('karyawan.unit_bisnis', $company->unit_bisnis)
                               ->select('requests_attendence.*')
                               ->orderBy('requests_attendence.tanggal', 'desc')
                               ->limit(50)
                               ->get();
        }
        

        return view('pages.absen.request.index', compact('dataRequest'));
    }

    public function updateStatusSetuju($id)
    {
        $userId = Auth::id();
        $EmployeeCode = Auth::user()->employee_code;

        $requestabsen = RequestAbsen::where('id', $id)->firstOrFail();
        if ($requestabsen->aprrove_status !== 'Approved') {
            $requestabsen->aprrove_status = 'Approved';
            $requestabsen->aprroved_by = $EmployeeCode;
            $requestabsen->save();

            $dataKaryawanRequest = $requestabsen->employee;
            $schedule = Schedule::where('employee',$dataKaryawanRequest)->where('tanggal', $requestabsen->tanggal)->first();
            
            // Cek status jika lupa absen
            if ($requestabsen->status = 'F') {
                // Simpan Kedalam Table Absen
                $absen = new Absen();
                $absen->user_id = $dataKaryawanRequest;
                $absen->nik = $dataKaryawanRequest;
                $absen->project = $schedule->project ?? '';
                $absen->tanggal = $requestabsen->tanggal;
                $absen->clock_in = $requestabsen->clock_in;
                $absen->clock_out = $requestabsen->clock_out;
                $absen->latitude = '-';
                $absen->longtitude = '-';
                $absen->status = $requestabsen->status;
                $absen->save();
            } else {
                // Simpan Kedalam Table Absen
                $absen = new Absen();
                $absen->user_id = $dataKaryawanRequest;
                $absen->nik = $dataKaryawanRequest;
                $absen->tanggal = $requestabsen->tanggal;
                $absen->clock_in = '-';
                $absen->latitude = '-';
                $absen->longtitude = '-';
                $absen->status = $requestabsen->status;
                $absen->save();
            }
            
        }

        return redirect()->back()->with('success', 'Data Pengajuan Berhasil Diupdate.');
    }

    public function updateStatusReject($id)
    {
        $userId = Auth::id();
        $EmployeeCode = Auth::user()->employee_code;

        $requestabsen = RequestAbsen::where('id', $id)->firstOrFail();
        if ($requestabsen->aprrove_status !== 'Reject') {
            $requestabsen->aprrove_status = 'Reject';
            $requestabsen->aprroved_by = $EmployeeCode;
            $requestabsen->save();
        }

        return redirect()->back()->with('success', 'Data Pengajuan Berhasil Diupdate.');
    }

    public function download($id)
    {
        try {
            $requestabsen = RequestAbsen::where('id', $id)->firstOrFail();
        
            $file_path = storage_path('app/' . $requestabsen->dokumen);
        
            return response()->download($file_path);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            // Handle the case when no data is found
            return redirect()->back()->with('error','Data not found.');
        } catch (\Exception $e) {
            // Handle other exceptions
            return redirect()->back()->with('error','Data not found.');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $userId = Auth::id();
        $EmployeeCode = Auth::user()->employee_code;
        $company = Employee::where('nik',$EmployeeCode)->select('unit_bisnis')->first();

        $historyData = RequestAbsen::where('employee', $EmployeeCode)->get();
        $typeRequest = RequestType::where('company', $company->unit_bisnis)->get();

        return view('pages.absen.request.create', compact('EmployeeCode','historyData','typeRequest'));
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
            'tanggal' => 'required'
        ]);
        $randomNumber = mt_rand(100000, 999999);
        $pengajuan = new RequestAbsen();
        $pengajuan->unik_code = $randomNumber;
        $pengajuan->tanggal = $request->input('tanggal');
        $pengajuan->employee = $request->input('employee');
        $pengajuan->jam_lembur = $request->input('jam_lembur');
        $pengajuan->clock_in = $request->input('clock_in');
        $pengajuan->clock_out = $request->input('clock_out');
        $pengajuan->status = $request->input('status');
        $pengajuan->alasan = $request->input('alasan');
        $pengajuan->aprrove_status = $request->input('aprrove_status');
        if ($request->hasFile('dokumen')) {
            $file = $request->file('dokumen');
            
            // Mendapatkan ekstensi file
            $extension = $file->getClientOriginalExtension();
        
            // Mengecek apakah file adalah PDF atau JPG
            if ($extension !== 'pdf' && $extension !== 'jpg') {
                return redirect()->back()->with('error', 'Hanya file PDF dan JPG yang diizinkan.');
            }
        
            // Jika file adalah PDF atau JPG maka simpan
            $path = $file->store('public/files');
            $pengajuan->dokumen = $path;
        }
        
        $pengajuan->save();

        return redirect()->back()->with('success', 'Pengajuan Berhasil Diajukan');
    }

    public function bulk_lembur(){
        // Loop through each request data if you have multiple entries
        $json_data = '[
            {
            "employee": "3301011907820003",
            "tanggal": "2024-11-21",
            "project": 116646,
            "clock_in": "12:00",
            "clock_out": "15:00",
            "status": "Lembur",
            "jam_lembur": 3,
            "approve_status": "Pending"
            },
            {
            "employee": "7106030403810003",
            "tanggal": "2024-11-21",
            "project": 116646,
            "clock_in": "12:00",
            "clock_out": "15:00",
            "status": "Lembur",
            "jam_lembur": 3,
            "approve_status": "Pending"
            },
            {
            "employee": "3301011907820003",
            "tanggal": "2024-11-22",
            "project": 116646,
            "clock_in": "12:00",
            "clock_out": "15:00",
            "status": "Lembur",
            "jam_lembur": 3,
            "approve_status": "Pending"
            },
            {
            "employee": "3376043105970002",
            "tanggal": "2024-11-22",
            "project": 116646,
            "clock_in": "12:00",
            "clock_out": "15:00",
            "status": "Lembur",
            "jam_lembur": 3,
            "approve_status": "Pending"
            },
            {
            "employee": "3174090710010003\t",
            "tanggal": "2024-11-25",
            "project": 116646,
            "clock_in": "12:00",
            "clock_out": "15:00",
            "status": "Lembur",
            "jam_lembur": 3,
            "approve_status": "Pending"
            },
            {
            "employee": "3329092308010002",
            "tanggal": "2024-11-25",
            "project": 116646,
            "clock_in": "12:00",
            "clock_out": "15:00",
            "status": "Lembur",
            "jam_lembur": 3,
            "approve_status": "Pending"
            },
            {
            "employee": "3172022606780003\t",
            "tanggal": "2024-11-26",
            "project": 116646,
            "clock_in": "12:00",
            "clock_out": "15:00",
            "status": "Lembur",
            "jam_lembur": 3,
            "approve_status": "Pending"
            },
            {
            "employee": "3174090710010003",
            "tanggal": "2024-11-26",
            "project": 116646,
            "clock_in": "12:00",
            "clock_out": "15:00",
            "status": "Lembur",
            "jam_lembur": 3,
            "approve_status": "Pending"
            },
            {
            "employee": "3376043105970002",
            "tanggal": "2024-11-28",
            "project": 116646,
            "clock_in": "12:00",
            "clock_out": "15:00",
            "status": "Lembur",
            "jam_lembur": 3,
            "approve_status": "Pending"
            },
            {
            "employee": "7106030403810003",
            "tanggal": "2024-11-28",
            "project": 116646,
            "clock_in": "12:00",
            "clock_out": "15:00",
            "status": "Lembur",
            "jam_lembur": 3,
            "approve_status": "Pending"
            },
            {
            "employee": "3173040602910001",
            "tanggal": "2024-11-29",
            "project": 116646,
            "clock_in": "12:00",
            "clock_out": "15:00",
            "status": "Lembur",
            "jam_lembur": 3,
            "approve_status": "Pending"
            },
            {
            "employee": "3171072505030005",
            "tanggal": "2024-11-29",
            "project": 116646,
            "clock_in": "12:00",
            "clock_out": "15:00",
            "status": "Lembur",
            "jam_lembur": 3,
            "approve_status": "Pending"
            },
            {
            "employee": "3172022606780003\t",
            "tanggal": "2024-12-02",
            "project": 116646,
            "clock_in": "12:00",
            "clock_out": "15:00",
            "status": "Lembur",
            "jam_lembur": 3,
            "approve_status": "Pending"
            },
            {
            "employee": "3174090710010003",
            "tanggal": "2024-12-02",
            "project": 116646,
            "clock_in": "12:00",
            "clock_out": "15:00",
            "status": "Lembur",
            "jam_lembur": 3,
            "approve_status": "Pending"
            },
            {
            "employee": "3301011907820003",
            "tanggal": "2024-12-03",
            "project": 116646,
            "clock_in": "12:00",
            "clock_out": "15:00",
            "status": "Lembur",
            "jam_lembur": 3,
            "approve_status": "Pending"
            },
            {
            "employee": "3171072505030005",
            "tanggal": "2024-12-03",
            "project": 116646,
            "clock_in": "12:00",
            "clock_out": "15:00",
            "status": "Lembur",
            "jam_lembur": 3,
            "approve_status": "Pending"
            },
            {
            "employee": "3301011907820003",
            "tanggal": "2024-12-04",
            "project": 116646,
            "clock_in": "12:00",
            "clock_out": "15:00",
            "status": "Lembur",
            "jam_lembur": 3,
            "approve_status": "Pending"
            },
            {
            "employee": "7106030403810003",
            "tanggal": "2024-12-04",
            "project": 116646,
            "clock_in": "12:00",
            "clock_out": "15:00",
            "status": "Lembur",
            "jam_lembur": 3,
            "approve_status": "Pending"
            },
            {
            "employee": "3173012705000003",
            "tanggal": "2024-12-05",
            "project": 116646,
            "clock_in": "12:00",
            "clock_out": "15:00",
            "status": "Lembur",
            "jam_lembur": 3,
            "approve_status": "Pending"
            },
            {
            "employee": "3171072505030005",
            "tanggal": "2024-12-05",
            "project": 116646,
            "clock_in": "12:00",
            "clock_out": "15:00",
            "status": "Lembur",
            "jam_lembur": 3,
            "approve_status": "Pending"
            },
            {
            "employee": "3173040602910001",
            "tanggal": "2024-12-06",
            "project": 116646,
            "clock_in": "12:00",
            "clock_out": "15:00",
            "status": "Lembur",
            "jam_lembur": 3,
            "approve_status": "Pending"
            },
            {
            "employee": "3173012705000003",
            "tanggal": "2024-12-06",
            "project": 116646,
            "clock_in": "12:00",
            "clock_out": "15:00",
            "status": "Lembur",
            "jam_lembur": 3,
            "approve_status": "Pending"
            },
            {
            "employee": "3301011907820003",
            "tanggal": "2024-12-09",
            "project": 116646,
            "clock_in": "12:00",
            "clock_out": "15:00",
            "status": "Lembur",
            "jam_lembur": 3,
            "approve_status": "Pending"
            },
            {
            "employee": "3171072505030005",
            "tanggal": "2024-12-09",
            "project": 116646,
            "clock_in": "12:00",
            "clock_out": "15:00",
            "status": "Lembur",
            "jam_lembur": 3,
            "approve_status": "Pending"
            },
            {
            "employee": "3301011907820003",
            "tanggal": "2024-12-10",
            "project": 116646,
            "clock_in": "12:00",
            "clock_out": "15:00",
            "status": "Lembur",
            "jam_lembur": 3,
            "approve_status": "Pending"
            },
            {
            "employee": "7106030403810003",
            "tanggal": "2024-12-10",
            "project": 116646,
            "clock_in": "12:00",
            "clock_out": "15:00",
            "status": "Lembur",
            "jam_lembur": 3,
            "approve_status": "Pending"
            },
            {
            "employee": "3173042509900002",
            "tanggal": "2024-12-14",
            "project": 116646,
            "clock_in": "12:00",
            "clock_out": "15:00",
            "status": "Lembur",
            "jam_lembur": 3,
            "approve_status": "Pending"
            },
            {
            "employee": "3172022606780003",
            "tanggal": "2024-12-14",
            "project": 116646,
            "clock_in": "12:00",
            "clock_out": "15:00",
            "status": "Lembur",
            "jam_lembur": 3,
            "approve_status": "Pending"
            },
            {
            "employee": "3216081807930007",
            "tanggal": "2024-11-21",
            "project": 173174,
            "clock_in": "19:00",
            "clock_out": "07:00",
            "status": "Lembur",
            "jam_lembur": 12,
            "approve_status": "Pending"
            },
            {
            "employee": "1608166008000001",
            "tanggal": "2024-11-23",
            "project": 173174,
            "clock_in": "17:00",
            "clock_out": "22:00",
            "status": "Lembur",
            "jam_lembur": 5,
            "approve_status": "Pending"
            },
            {
            "employee": "3674073112940001",
            "tanggal": "2024-11-23",
            "project": 173174,
            "clock_in": "17:00",
            "clock_out": "22:00",
            "status": "Lembur",
            "jam_lembur": 5,
            "approve_status": "Pending"
            },
            {
            "employee": "1611040408950005",
            "tanggal": "2024-11-23",
            "project": 173174,
            "clock_in": "17:00",
            "clock_out": "22:00",
            "status": "Lembur",
            "jam_lembur": 5,
            "approve_status": "Pending"
            },
            {
            "employee": "1701041411980001",
            "tanggal": "2024-11-23",
            "project": 173174,
            "clock_in": "17:00",
            "clock_out": "22:00",
            "status": "Lembur",
            "jam_lembur": 5,
            "approve_status": "Pending"
            },
            {
            "employee": "3215242204000002",
            "tanggal": "2024-11-26",
            "project": 173174,
            "clock_in": "07:00",
            "clock_out": "19:00",
            "status": "Lembur",
            "jam_lembur": 12,
            "approve_status": "Pending"
            },
            {
            "employee": "3302030609850002",
            "tanggal": "2024-11-26",
            "project": 173174,
            "clock_in": "07:00",
            "clock_out": "19:00",
            "status": "Lembur",
            "jam_lembur": 12,
            "approve_status": "Pending"
            },
            {
            "employee": "1801212512020001",
            "tanggal": "2024-11-26",
            "project": 173174,
            "clock_in": "07:00",
            "clock_out": "19:00",
            "status": "Lembur",
            "jam_lembur": 12,
            "approve_status": "Pending"
            },
            {
            "employee": "3203061502030005",
            "tanggal": "2024-11-26",
            "project": 173174,
            "clock_in": "07:00",
            "clock_out": "19:00",
            "status": "Lembur",
            "jam_lembur": 12,
            "approve_status": "Pending"
            },
            {
            "employee": "1608110107830003",
            "tanggal": "2024-11-29",
            "project": 173174,
            "clock_in": "16:00",
            "clock_out": "22:00",
            "status": "Lembur",
            "jam_lembur": 6,
            "approve_status": "Pending"
            },
            {
            "employee": "3329034308000002",
            "tanggal": "2024-11-29",
            "project": 173174,
            "clock_in": "16:00",
            "clock_out": "22:00",
            "status": "Lembur",
            "jam_lembur": 6,
            "approve_status": "Pending"
            },
            {
            "employee": "3172032706980002\t",
            "tanggal": "2024-11-29",
            "project": 173174,
            "clock_in": "16:00",
            "clock_out": "22:00",
            "status": "Lembur",
            "jam_lembur": 6,
            "approve_status": "Pending"
            },
            {
            "employee": "3271012811010012",
            "tanggal": "2024-11-30",
            "project": 173174,
            "clock_in": "16:00",
            "clock_out": "22:00",
            "status": "Lembur",
            "jam_lembur": 6,
            "approve_status": "Pending"
            },
            {
            "employee": "3601285701040002\t",
            "tanggal": "2024-11-30",
            "project": 173174,
            "clock_in": "16:00",
            "clock_out": "22:00",
            "status": "Lembur",
            "jam_lembur": 6,
            "approve_status": "Pending"
            },
            {
            "employee": "3213261008980001",
            "tanggal": "2024-11-30",
            "project": 173174,
            "clock_in": "17:00",
            "clock_out": "01:00",
            "status": "Lembur",
            "jam_lembur": 8,
            "approve_status": "Pending"
            },
            {
            "employee": "3271042907990006",
            "tanggal": "2024-11-30",
            "project": 173174,
            "clock_in": "16:00",
            "clock_out": "01:00",
            "status": "Lembur",
            "jam_lembur": 9,
            "approve_status": "Pending"
            },
            {
            "employee": "3174062210010003",
            "tanggal": "2024-11-30",
            "project": 173174,
            "clock_in": "16:00",
            "clock_out": "01:00",
            "status": "Lembur",
            "jam_lembur": 9,
            "approve_status": "Pending"
            },
            {
            "employee": "1608186109040001",
            "tanggal": "2024-12-02",
            "project": 173174,
            "clock_in": "12:30",
            "clock_out": "18:00",
            "status": "Lembur",
            "jam_lembur": 6,
            "approve_status": "Pending"
            },
            {
            "employee": "3173070711020001",
            "tanggal": "2024-12-02",
            "project": 173174,
            "clock_in": "12:30",
            "clock_out": "18:00",
            "status": "Lembur",
            "jam_lembur": 6,
            "approve_status": "Pending"
            },
            {
            "employee": "1801214509060001\t",
            "tanggal": "2024-12-03",
            "project": 173174,
            "clock_in": "08:00",
            "clock_out": "14:00",
            "status": "Lembur",
            "jam_lembur": 6,
            "approve_status": "Pending"
            },
            {
            "employee": "3201141603030004",
            "tanggal": "2024-12-03",
            "project": 173174,
            "clock_in": "08:00",
            "clock_out": "14:00",
            "status": "Lembur",
            "jam_lembur": 6,
            "approve_status": "Pending"
            },
            {
            "employee": "3174011807031004",
            "tanggal": "2024-12-03",
            "project": 173174,
            "clock_in": "19:00",
            "clock_out": "07:00",
            "status": "Lembur",
            "jam_lembur": 12,
            "approve_status": "Pending"
            },
            {
            "employee": "3329022004990009",
            "tanggal": "2024-12-04",
            "project": 173174,
            "clock_in": "07:00",
            "clock_out": "19:00",
            "status": "Lembur",
            "jam_lembur": 12,
            "approve_status": "Pending"
            },
            {
            "employee": "1608166008000001",
            "tanggal": "2024-12-05",
            "project": 173174,
            "clock_in": "19:00",
            "clock_out": "07:00",
            "status": "Lembur",
            "jam_lembur": 12,
            "approve_status": "Pending"
            },
            {
            "employee": "3201170209970006",
            "tanggal": "2024-12-05",
            "project": 173174,
            "clock_in": "19:00",
            "clock_out": "07:00",
            "status": "Lembur",
            "jam_lembur": 12,
            "approve_status": "Pending"
            },
            {
            "employee": "1605111908030003",
            "tanggal": "2024-12-05",
            "project": 173174,
            "clock_in": "19:00",
            "clock_out": "07:00",
            "status": "Lembur",
            "jam_lembur": 12,
            "approve_status": "Pending"
            },
            {
            "employee": "1611040408950005",
            "tanggal": "2024-12-06",
            "project": 173174,
            "clock_in": "10:30",
            "clock_out": "17:30",
            "status": "Lembur",
            "jam_lembur": 7,
            "approve_status": "Pending"
            },
            {
            "employee": "1701041411980001",
            "tanggal": "2024-12-06",
            "project": 173174,
            "clock_in": "10:30",
            "clock_out": "17:30",
            "status": "Lembur",
            "jam_lembur": 7,
            "approve_status": "Pending"
            },
            {
            "employee": "3201314907020004",
            "tanggal": "2024-12-07",
            "project": 173174,
            "clock_in": "16:00",
            "clock_out": "22:00",
            "status": "Lembur",
            "jam_lembur": 6,
            "approve_status": "Pending"
            },
            {
            "employee": "3203060710970006",
            "tanggal": "2024-12-07",
            "project": 173174,
            "clock_in": "16:00",
            "clock_out": "22:00",
            "status": "Lembur",
            "jam_lembur": 6,
            "approve_status": "Pending"
            },
            {
            "employee": "3203061502030005",
            "tanggal": "2024-12-07",
            "project": 173174,
            "clock_in": "16:00",
            "clock_out": "22:00",
            "status": "Lembur",
            "jam_lembur": 6,
            "approve_status": "Pending"
            },
            {
            "employee": "1808144104050001",
            "tanggal": "2024-12-10",
            "project": 173174,
            "clock_in": "16:00",
            "clock_out": "22:00",
            "status": "Lembur",
            "jam_lembur": 6,
            "approve_status": "Pending"
            },
            {
            "employee": "3275083005020025",
            "tanggal": "2024-12-10",
            "project": 173174,
            "clock_in": "16:00",
            "clock_out": "22:00",
            "status": "Lembur",
            "jam_lembur": 6,
            "approve_status": "Pending"
            },
            {
            "employee": "1608110107830003",
            "tanggal": "2024-12-12",
            "project": 173174,
            "clock_in": "07:00",
            "clock_out": "19:00",
            "status": "Lembur",
            "jam_lembur": 12,
            "approve_status": "Pending"
            },
            {
            "employee": "1801061105970003",
            "tanggal": "2024-12-12",
            "project": 173174,
            "clock_in": "07:00",
            "clock_out": "19:00",
            "status": "Lembur",
            "jam_lembur": 12,
            "approve_status": "Pending"
            },
            {
            "employee": "1306035704010001",
            "tanggal": "2024-12-13",
            "project": 173174,
            "clock_in": "15:00",
            "clock_out": "22:00",
            "status": "Lembur",
            "jam_lembur": 7,
            "approve_status": "Pending"
            },
            {
            "employee": "3173062110990002",
            "tanggal": "2024-12-13",
            "project": 173174,
            "clock_in": "15:00",
            "clock_out": "22:00",
            "status": "Lembur",
            "jam_lembur": 7,
            "approve_status": "Pending"
            },
            {
            "employee": "3302030609850002",
            "tanggal": "2024-12-13",
            "project": 173174,
            "clock_in": "19:00",
            "clock_out": "07:00",
            "status": "Lembur",
            "jam_lembur": 12,
            "approve_status": "Pending"
            },
            {
            "employee": "1806090306950002",
            "tanggal": "2024-12-13",
            "project": 173174,
            "clock_in": "19:00",
            "clock_out": "07:00",
            "status": "Lembur",
            "jam_lembur": 12,
            "approve_status": "Pending"
            },
            {
            "employee": "1801210506010001",
            "tanggal": "2024-12-14",
            "project": 173174,
            "clock_in": "19:00",
            "clock_out": "07:00",
            "status": "Lembur",
            "jam_lembur": 12,
            "approve_status": "Pending"
            },
            {
            "employee": "3203061008000010",
            "tanggal": "2024-12-14",
            "project": 173174,
            "clock_in": "19:00",
            "clock_out": "07:00",
            "status": "Lembur",
            "jam_lembur": 12,
            "approve_status": "Pending"
            },
            {
            "employee": "3213261008980001",
            "tanggal": "2024-12-14",
            "project": 173174,
            "clock_in": "20:00",
            "clock_out": "04:00",
            "status": "Lembur",
            "jam_lembur": 12,
            "approve_status": "Pending"
            },
            {
            "employee": "1803236709040001",
            "tanggal": "2024-12-15",
            "project": 173174,
            "clock_in": "17:00",
            "clock_out": "22:00",
            "status": "Lembur",
            "jam_lembur": 5,
            "approve_status": "Pending"
            },
            {
            "employee": "3275083005020025",
            "tanggal": "2024-12-15",
            "project": 173174,
            "clock_in": "17:00",
            "clock_out": "22:00",
            "status": "Lembur",
            "jam_lembur": 5,
            "approve_status": "Pending"
            },
            {
            "employee": "3174011807031004",
            "tanggal": "2024-12-15",
            "project": 173174,
            "clock_in": "17:00",
            "clock_out": "22:00",
            "status": "Lembur",
            "jam_lembur": 5,
            "approve_status": "Pending"
            },
            {
            "employee": "1809061109960004",
            "tanggal": "2024-12-15",
            "project": 173174,
            "clock_in": "19:00",
            "clock_out": "07:00",
            "status": "Lembur",
            "jam_lembur": 12,
            "approve_status": "Pending"
            },
            {
            "employee": "3329060309910001",
            "tanggal": "2024-12-15",
            "project": 173174,
            "clock_in": "19:00",
            "clock_out": "07:00",
            "status": "Lembur",
            "jam_lembur": 12,
            "approve_status": "Pending"
            },
            {
            "employee": "3213261008980001",
            "tanggal": "2024-12-15",
            "project": 173174,
            "clock_in": "10:00",
            "clock_out": "22:00",
            "status": "Lembur",
            "jam_lembur": 12,
            "approve_status": "Pending"
            },
            {
            "employee": "3201031704690004",
            "tanggal": "2024-12-16",
            "project": 173174,
            "clock_in": "07:00",
            "clock_out": "19:00",
            "status": "Lembur",
            "jam_lembur": 12,
            "approve_status": "Pending"
            },
            {
            "employee": "3201141603030004",
            "tanggal": "2024-12-17",
            "project": 173174,
            "clock_in": "07:00",
            "clock_out": "12:00",
            "status": "Lembur",
            "jam_lembur": 5,
            "approve_status": "Pending"
            },
            {
            "employee": "3271042907990006",
            "tanggal": "2024-12-17",
            "project": 173174,
            "clock_in": "19:00",
            "clock_out": "07:00",
            "status": "Lembur",
            "jam_lembur": 12,
            "approve_status": "Pending"
            },
            {
            "employee": "3201170209970006\t",
            "tanggal": "2024-12-17",
            "project": 173174,
            "clock_in": "19:00",
            "clock_out": "07:00",
            "status": "Lembur",
            "jam_lembur": 12,
            "approve_status": "Pending"
            },
            {
            "employee": "1608130108010001",
            "tanggal": "2024-12-18",
            "project": 173174,
            "clock_in": "21:00",
            "clock_out": "02:00",
            "status": "Lembur",
            "jam_lembur": 5,
            "approve_status": "Pending"
            },
            {
            "employee": "3172032706980002",
            "tanggal": "2024-12-18",
            "project": 173174,
            "clock_in": "21:00",
            "clock_out": "02:00",
            "status": "Lembur",
            "jam_lembur": 5,
            "approve_status": "Pending"
            },
            {
            "employee": "3271012811010012",
            "tanggal": "2024-12-18",
            "project": 173174,
            "clock_in": "21:00",
            "clock_out": "02:00",
            "status": "Lembur",
            "jam_lembur": 5,
            "approve_status": "Pending"
            },
            {
            "employee": "1701041411980001",
            "tanggal": "2024-12-18",
            "project": 173174,
            "clock_in": "21:00",
            "clock_out": "02:00",
            "status": "Lembur",
            "jam_lembur": 5,
            "approve_status": "Pending"
            },
            {
            "employee": "3173064401040009",
            "tanggal": "2024-12-18",
            "project": 173174,
            "clock_in": "07:00",
            "clock_out": "12:00",
            "status": "Lembur",
            "jam_lembur": 5,
            "approve_status": "Pending"
            },
            {
            "employee": "1611040408950005",
            "tanggal": "2024-12-18",
            "project": 173174,
            "clock_in": "07:00",
            "clock_out": "12:00",
            "status": "Lembur",
            "jam_lembur": 5,
            "approve_status": "Pending"
            },
            {
            "employee": "3173062110990002",
            "tanggal": "2024-12-19",
            "project": 173174,
            "clock_in": "07:00",
            "clock_out": "13:00",
            "status": "Lembur",
            "jam_lembur": 6,
            "approve_status": "Pending"
            },
            {
            "employee": "3173070711020001",
            "tanggal": "2024-12-20",
            "project": 173174,
            "clock_in": "07:00",
            "clock_out": "13:00",
            "status": "Lembur",
            "jam_lembur": 7,
            "approve_status": "Pending"
            }
        ]';
        foreach (json_decode($json_data) as $data) {
            $randomNumber = mt_rand(100000, 999999);
            $pengajuan = new RequestAbsen();
            $pengajuan->unik_code = $randomNumber;
            $pengajuan->tanggal = $data->tanggal; // Assumes 'tanggal' is the date key in the input
            $pengajuan->employee = $data->employee; // Employee ID
            $pengajuan->jam_lembur = $data->jam_lembur; // Overtime hours
            $pengajuan->clock_in = $data->clock_in; // Clock In time
            $pengajuan->clock_out = $data->clock_out; // Clock Out time
            $pengajuan->status = $data->status; // Status
            $pengajuan->alasan = '-'; // Reason (if any)
            $pengajuan->aprrove_status = $data->approve_status; // Corrected the spelling of 'approve_status'

            

            // Save the pengajuan record
            $pengajuan->save();
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
