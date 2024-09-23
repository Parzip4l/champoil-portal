<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;
use App\Employee;
use App\EmployeeResign;
use App\Absen;
use App\Backup\AbsenBackup;
use App\User;
use App\Payrolinfo\Payrolinfo;
use App\UserActivities;
use App\ModelCG\Jabatan;
use App\Divisi\Divisi;
Use App\Organisasi\Organisasi;
use App\ModelCG\Schedule;
use App\ModelCG\ScheduleBackup;
use App\ModelCG\Project;
use Carbon\Carbon;
use App\Absen\RequestAbsen;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Exports\EmployeeExport;
use App\Imports\EmployeeImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Setting\Golongan\GolonganModel;
use Yajra\DataTables\Facades\DataTables;
use App\Mail\NewEmployee;
use Illuminate\Support\Facades\Mail;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $code = Auth::user()->employee_code;
        $company = Employee::where('nik', $code)->first();
        $data['jenis_kelamin'] = Employee::selectRaw('jenis_kelamin, COUNT(*) as total')
                                ->groupBy('jenis_kelamin')
                                ->get();
        $data['sertifikasi'] = Employee::selectRaw('sertifikasi, COUNT(*) as total')
                                ->where('sertifikasi','!=',NULL)
                                ->where('sertifikasi','!=','')
                                ->groupBy('sertifikasi')
                                ->get();
        $data['jabatan'] = Employee::selectRaw('jabatan, COUNT(*) as total')
                                ->where('jabatan','!=',NULL)
                                ->where('jabatan','!=','')
                                ->groupBy('jabatan')
                                ->get();
        
        // dd($request->input('jenis_kelamin'));
        if ($request->ajax()) {
            if (Auth::user()->project_id == NULL) {
                $query = Employee::where('unit_bisnis', $company->unit_bisnis)
                                ->where('resign_status', 0);
            } else {
                $query = Employee::select('karyawan.*')
                                ->where('unit_bisnis', $company->unit_bisnis)
                                ->join('schedules', function($join) {
                                    $join->on('karyawan.nik', '=', 'schedules.employee')
                                        ->where('schedules.project', Auth::user()->project_id)
                                        ->where('schedules.id', '=', function($query) {
                                            $query->select('id')
                                                ->from('schedules')
                                                ->whereColumn('employee', 'karyawan.nik')
                                                ->where('schedules.project', Auth::user()->project_id)
                                                ->limit(1);
                                        });
                                })
                                ->where('resign_status', 0);
                
            }

            if(!empty($request->input('jenis_kelamin'))){
                $query->where('jenis_kelamin',$request->input('jenis_kelamin'));
            }

            if(!empty($request->input('sertifikasi'))){
                $query->where('sertifikasi',$request->input('sertifikasi'));
            }

            if(!empty($request->input('jabatan'))){
                $query->where('jabatan',$request->input('jabatan'));
            }

            if(!empty($request->input('bpjs'))){
                if($request->input('bpjs')==0){
                    $query->leftJoin('payrolinfos','payrolinfos.employee_code','=','karyawan.nik');
                    $query->whereIn('payrolinfos.bpjs_tk',[NULL,0]);
                }else{
                    $query->leftJoin('payrolinfos','payrolinfos.employee_code','=','karyawan.nik');
                    $query->whereNotIn('payrolinfos.bpjs_tk',[NULL,0]);
                }
                
            }

            return DataTables::of($query)
                ->addColumn('action', function($data) {
                    return view('partials.action-employee', compact('data'));
                })
                ->editColumn('status_kontrak', function ($data) {
                    $badgeClass = $data->status_kontrak == 'Permanent' ? 'bg-primary' : 'bg-success';
                    return '<span class="badge rounded-pill ' . $badgeClass . '">' . $data->status_kontrak . '</span>';
                })
                ->rawColumns(['action', 'status_kontrak'])
                ->make(true);
        }

        return view('pages.hc.karyawan.index',$data);
    }

    public function ApiEmployee(Request $request)
    {
        try {
            // Get the token from the Authorization header
            $token = $request->bearerToken();
            // Check if the token is valid
            $user = Auth::guard('api')->user();

            if ($user) {
                $code = $user->employee_code;

                // Ensure the "employee_code" property exists in the user object
                if ($code) {
                    // Define the cache key
                    $cacheKey = 'employee_data:' . $code;

                    // Check if data is already in cache
                    $cachedData = Cache::get($cacheKey);
                    if ($cachedData) {
                        return response()->json(['karyawan' => $cachedData], 200);
                    }

                    $company = Employee::where('nik', $code)->first();

                    // Ensure the "company" object is not null before accessing the "unit_bisnis" property
                    if ($company) {
                        $karyawan = Employee::where('unit_bisnis', $company->unit_bisnis)->get();

                        // Store data in cache for future requests
                        Cache::put($cacheKey, $karyawan, 60); // Set expiration time in minutes

                        return response()->json(['karyawan' => $karyawan], 200);
                    } else {
                        return response()->json(['error' => 'Data perusahaan tidak ditemukan.'], 404);
                    }
                } else {
                    return response()->json(['error' => 'Properti "employee_code" tidak ditemukan pada pengguna.'], 400);
                }
            } else {
                return response()->json(['error' => 'Token tidak valid atau pengguna tidak terautentikasi.'], 401);
            }
        } catch (\Exception $e) {
            // Handle general errors
            return response()->json(['error' => 'Terjadi kesalahan.'], 500);
        }
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
        
        $jabatan = Jabatan::where('parent_category', $company->unit_bisnis)->get();
        $divisi = Divisi::where('company', $company->unit_bisnis)->get();
        $organisasi = Organisasi::where('company', $company->unit_bisnis)->get();
        $project = Project::all();

        $golongan = GolonganModel::where('company', $company->unit_bisnis)->get();
        $atasan = Employee::where('unit_bisnis',$company->unit_bisnis)->where('resign_status',0)->where('organisasi', 'Management Leaders')->get();

        return view('pages.hc.karyawan.create', compact('jabatan','divisi','organisasi','project','golongan','atasan'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try{
            $code = Auth::user()->employee_code;
            $company = Employee::where('nik', $code)->first();

            $request->validate([
                'nama' => 'required',
                'gambar' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
            ]);
            
            DB::beginTransaction();
            $data = new Employee();
            $data->ktp = $request->ktp;
            $data->nik = $request->nik;
            $data->nama = $request->nama;
            $data->alamat = $request->alamat;
            $data->alamat_ktp = $request->alamat_ktp;
            $data->divisi = $request->divisi;
            $data->jabatan = $request->jabatan;
            $data->organisasi = $request->organisasi;
            $data->status_kontrak = $request->status_kontrak;
            $data->joindate = $request->joindate;
            $data->berakhirkontrak = $request->berakhirkontrak;
            $data->email = $request->email;
            $data->pendidikan_trakhir = $request->pendidikan_trakhir;
            $data->jurusan = $request->jurusan;
            $data->sertifikasi = $request->sertifikasi;
            $data->expired_sertifikasi = $request->expired_sertifikasi;
            $data->telepon = $request->telepon;
            $data->telepon_darurat = $request->telepon_darurat;
            $data->status_pernikahan = $request->status_pernikahan;
            $data->agama = $request->agama;
            $data->tanggal_lahir = $request->tanggal_lahir;
            $data->tempat_lahir = $request->tempat_lahir;
            $data->jenis_kelamin = $request->jenis_kelamin;
            $data->tanggungan = $request->tanggungan;
            $data->tax_code = $request->tax_code;
            $data->unit_bisnis = $company->unit_bisnis;
            $data->golongan = $request->level;
            $data->manager = $request->manager;
            $data->slack_id = $request->slack_id;
            $data->referal_code = $this->generateCodeVisitor("karyawan","id", 5, "CITY");

            if ($request->hasFile('gambar')) {
                $image = $request->file('gambar');
                $filename = time() . '.' . $image->getClientOriginalExtension();
                $destinationPath = public_path('/images');
                $image->move($destinationPath, $filename);
                $data->gambar = $filename;
            }
            $data->save();
            // Payrol Info
            $payrolinfo = new Payrolinfo();
            $payrolinfo->employee_code = $request->nik;
            $payrolinfo->bpjs_kes = $request->bpjs_kes;
            $payrolinfo->bpjs_tk = $request->bpjs_tk;
            $payrolinfo->npwp = $request->npwp;
            $payrolinfo->bank_name = $request->bank_name;
            $payrolinfo->bank_number = $request->bank_number;
            $payrolinfo->ptkp = $request->tanggungan;
            $payrolinfo->save();

            // User Info
            $userinfo = new User();
            $userinfo->name = $request->nik;
            $userinfo->email = $request->email;
            $userinfo->project_id = $request->project_id;
            $userinfo->password = Hash::make($request->password);
            $userinfo->permission = json_encode($request->permissions);
            $userinfo->employee_code = $request->nik;
            $userinfo->company = $company->unit_bisnis;
            $userinfo->save();

            if($company->unit_bisnis=="Kas"){
                $unit="CITY SERVICE";
            }else if($company->unit_bisnis=="CHAMPOIL"){
                $unit="CHAMPOIL";
            }else{
                $unit="";
            }

            $html ="Hello ".strtoupper($request->nama)."

Welcome to ".$unit."! 🎉

Please install the TRUEST application for your digital attendance needs.

Here are the download links:

Play Store: https://play.google.com/store/apps/details?id=co.id.truest.truest

App Store: https://apps.apple.com/idn/app/truest/id6476389232?platform=iphone

And here is your login account information:
    
Email: ".$request->email."
Password: ".$request->password;

            push_notif_wa($html,'','',$request->telepon,'');
            Mail::to($request->email)->send(new NewEmployee($data));
            DB::commit();
            return redirect()->route('employee.index')->with(['success' => 'Data Berhasil Disimpan!']);
        }catch (ValidationException $exception) {
            DB::rollBack();
            $errorMessage = $exception->validator->errors()->first(); // ambil pesan error pertama dari validator
            redirect()->route('employee.index')->with('error', 'Gagal menyimpan data. ' . $errorMessage); // tambahkan alert error
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
        $employee = Employee::find($id);
        $nikdata = $employee->nik;

        $today = now();
        $startDate = $today->day >= 21 ? $today->copy()->day(20) : $today->copy()->subMonth()->day(21);
        $endDate = $today->day >= 21 ? $today->copy()->addMonth()->day(20) : $today->copy()->day(20);

        // Hitung jumlah hari kerja tanpa absensi (termasuk akhir pekan)
        $totalWorkingDays = $startDate->diffInWeekdays($endDate) + 1;

        // Fetch attendance data for the current month
        $attendanceData = DB::table('absens')
            ->where('nik', $nikdata)
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->orderBy('tanggal', 'asc')
            ->get();

        // Hitung jumlah hari dengan absensi
        $daysWithAttendance = count($attendanceData);

        // Hitung jumlah hari tanpa absensi
        $daysWithoutAttendance = $totalWorkingDays - $daysWithAttendance;

        // No ClockOut
        $daysWithClockInNoClockOut = 0;

        foreach ($attendanceData as $absendata) {
            if (!empty($absendata->clock_in) && empty($absendata->clock_out)) {
                $daysWithClockInNoClockOut++;
            }
        }

        // Sakit
        $sakit = 0;

        foreach ($attendanceData as $absendata) {
            if ($absendata->status == 'Sakit') {
                $sakit++;
            }
        }

        $izin = 0;

        foreach ($attendanceData as $absendata) {
            if ($absendata->status == 'Izin') {
                $izin++;
            }
        }

        // Request Absen
        $requestAbsen = RequestAbsen::where('employee',$nikdata)
                        ->whereBetween('tanggal', [$startDate, $endDate])
                        ->get();
        
        //count Request
        $CountRequest = count($requestAbsen);
        

        return view('pages.hc.karyawan.details', compact('employee', 'attendanceData', 'daysWithoutAttendance','daysWithClockInNoClockOut','daysWithAttendance','sakit','requestAbsen','CountRequest','izin'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $employee = Employee::where('nik', $id)->with('payrolinfo','user')->first();
        if(!empty($employee->referal_code)){
            $unix = $employee->referal_code;
        }else{
            $unix = $this->generateCodeVisitor("karyawan","id", 4, "CITY");
        }

        $golongan = GolonganModel::where('company', $employee->unit_bisnis)->get();
        $atasan = Employee::where('unit_bisnis',$employee->unit_bisnis)->where('resign_status',0)->get();

        $divisi = Divisi::where('company', $employee->unit_bisnis)->get();
        $jabatan = Jabatan::where('parent_category',$employee->unit_bisnis)->get();
        $organisasi = Organisasi::where('company',$employee->unit_bisnis)->get();

        return view('pages.hc.karyawan.edit', compact('employee','unix','divisi','jabatan','organisasi','golongan','atasan'));
        
    }

    public function getAttendanceData(Request $request) {
        $selectedMonth = $request->input('month');
        $selectedYear = $request->input('year');
        $nikData = $request->input('nik');

        // Hitung tanggal awal (start_date) dan tanggal akhir (end_date) berdasarkan bulan dan tahun yang dipilih
        $start_date = Carbon::create($selectedYear, $selectedMonth, 21, 0, 0, 0);
        $end_date = $start_date->copy()->addMonth()->day(20);

        // Buat array yang akan berisi data untuk setiap tanggal dalam rentang
        $tableData = [];

        // Loop melalui setiap tanggal dalam rentang
        $currentDate = $start_date->copy();
        while ($currentDate->lte($end_date)) {
            // Cari data absen untuk tanggal saat ini
            $attendanceData = Absen::where('nik',$nikData)->whereDate('tanggal', $currentDate)->first();

            // Buat array data untuk tanggal ini
            $rowData = [
                'tanggal' => $currentDate->format('Y-m-d'),
                'clock_in' => $attendanceData ? $attendanceData->clock_in : '-',
                'clock_out' => $attendanceData ? $attendanceData->clock_out : '-',
                'status' => $attendanceData ? $attendanceData->status : '-',
            ];

            // Tambahkan kolom tombol Edit jika diperlukan
            $rowData['edit_button'] = true;
            $rowData['delete_button'] = true;

            // Tambahkan kelas "text-danger" jika tanggal adalah hari Sabtu atau Minggu
            if ($currentDate->isWeekend()) {
                $rowData['is_weekend'] = true;
            }

            // Tambahkan data tanggal ini ke array utama
            $tableData[] = $rowData;

            // Pindah ke tanggal berikutnya
            $currentDate->addDay();
        }

        // Kembalikan data dalam format JSON
        return response()->json($tableData);
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
            $request->validate([
                'nama' => 'required|string|max:255',
                'ktp' => 'required|numeric',
            ]);
            $code = Auth::user()->employee_code;
            $company = Employee::where('nik', $code)->first();
            DB::beginTransaction();
            // Find the employee by ID
            $employee = Employee::where('nik', $id)->first();
            
            // Kembalikan jika tidak ditemukan
            if (!$employee) {
                return redirect()->back()->with('error', 'Employee not found.');
            }
            // Update the employee data
            $employee->nama = $request->input('nama');
            $employee->ktp = $request->input('ktp');
            $employee->nik = $request->input('nik');
            $employee->jabatan = $request->input('jabatan');
            $employee->divisi = $request->input('divisi');
            $employee->pendidikan_trakhir = $request->input('pendidikan_trakhir');
            $employee->jurusan = $request->input('jurusan');
            $employee->sertifikasi = $request->input('sertifikasi');
            $employee->expired_sertifikasi = $request->input('expired_sertifikasi');
            $employee->agama = $request->input('agama');
            $employee->jenis_kelamin = $request->input('jenis_kelamin');
            $employee->email = $request->input('email');
            $employee->telepon = $request->input('telepon');
            $employee->telepon_darurat = $request->input('telepon_darurat');
            $employee->status_kontrak = $request->input('status_kontrak');
            $employee->organisasi = $request->input('organisasi');
            $employee->joindate = $request->input('joindate');
            $employee->berakhirkontrak = $request->input('berakhirkontrak');
            $employee->tempat_lahir = $request->input('tempat_lahir');
            $employee->tanggal_lahir = $request->input('tanggal_lahir');
            $employee->alamat = $request->input('alamat');
            $employee->alamat_ktp = $request->input('alamat_ktp');
            $employee->status_pernikahan = $request->input('status_pernikahan');
            $employee->tanggungan = $request->input('tanggungan');
            $employee->tax_code = $request->input('tax_code');
            $employee->referal_code = $request->input('referal_code');
            $employee->golongan = $request->input('golongan');
            $employee->manager = $request->input('atasan_langsung');
            $employee->slack_id = $request->input('slack_id');
            

            // Update the employee's photo if a new one is provided
            if ($request->hasFile('gambar')) {
                $photo = $request->file('gambar');
                $photoFileName = time() . '.' . $photo->getClientOriginalExtension();
                $photo->move(public_path('/images'), $photoFileName);
                
                // Delete old photo if exists
                if ($employee->photo) {
                    $oldPhotoPath = public_path('/images') . $employee->photo;
                    if (file_exists($oldPhotoPath)) {
                        unlink($oldPhotoPath);
                    }
                }
                $employee->gambar = $photoFileName;
            }
            
            // Save the updated employee
            $employee->save();
            
            // Cek Payrol Info
            $payrollInfo = Payrolinfo::where('employee_code', $id)->first();

            // Payroll Info 
            if ($payrollInfo) {
                $payrollInfo->employee_code = $request->input('nik');
                $payrollInfo->bpjs_kes = $request->input('bpjs_kes');
                $payrollInfo->bpjs_tk = $request->input('bpjs_tk');
                $payrollInfo->npwp = $request->input('npwp');
                $payrollInfo->bank_name = $request->input('bank_name');
                $payrollInfo->bank_number = $request->input('bank_number');
                $payrollInfo->ptkp = $request->input('tanggungan');
                $payrollInfo->save();
            } else {
                // Jika tidak ditemukan maka buat baru
                $payrolinfo = new Payrolinfo();
                $payrolinfo->employee_code = $request->nik;
                $payrolinfo->bpjs_kes = $request->bpjs_kes;
                $payrolinfo->bpjs_tk = $request->bpjs_tk;
                $payrolinfo->npwp = $request->npwp;
                $payrolinfo->bank_name = $request->bank_name;
                $payrolinfo->bank_number = $request->bank_number;
                $payrolinfo->ptkp = $request->tanggungan;
                $payrolinfo->save();
            }

            $userInfo = User::where('name', $id)->first();

            if (!$userInfo) {
                $userInfo = new User();
                $userInfo->name = $request->nik;
                $userInfo->email = $request->email;
                $userInfo->password = Hash::make($request->password);
                $userInfo->permission = json_encode($request->permissions);
                $userInfo->employee_code = $request->nik;
                $userInfo->company = $company->unit_bisnis;
                $userInfo->save();
            }

            DB::commit();
            // Redirect to a view or return a response as needed
            return redirect()->back()->with('success', 'Employee data updated successfully.');
        }catch (ValidationException $exception) {
            DB::rollBack();
            $errorMessage = $exception->validator->errors()->first(); // ambil pesan error pertama dari validator
            if (!$employee->save()) {
                return redirect()->back()->with('error', 'Gagal menyimpan data karyawan.' . $errorMessage);
            }

            if (!$payrollInfo->save()) {
                return redirect()->back()->with('error', 'Gagal menyimpan data Payroll Info.' . $errorMessage);
            }
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
        $contact = Employee::find($id);
        if($contact){
            // $data=[
            //     "employee_code"=>$contact->nik,
            //     "nama"=>$contact->nama,
            //     "ktp"=>$contact->ktp,
            //     "join_date"=>$contact->joindate,
            //     "meta_karyawan"=>json_encode($contact),
            //     "created_at"=>date('Y-m-d H:i:s')
            // ];
            // $insert_resign = EmployeeResign::insertGetId($data);
            // if($insert_resign){
                $contact->delete();
                return redirect()->route('employee.index')->with('success', 'Employee Successfully Deleted');
            // }else{
            //     return redirect()->route('employee.index')->with('danger', 'Employee Failed Deleted');
            // }
        }
    }

    /**
     * Resign the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function resign(Request $request)
    {
        $contact = Employee::find($request->input('id'));
        $contact->reason = $request->input('reason');
        if($contact){
            $data=[
                "employee_code"=>$contact->nik,
                "nama"=>$contact->nama,
                "ktp"=>$contact->ktp,
                "join_date"=>$contact->joindate,
                "meta_karyawan"=>json_encode($contact),
                "created_at"=>date('Y-m-d H:i:s'),
                "unit_bisnis"=>$contact->unit_bisnis
            ];
            $insert_resign = EmployeeResign::insertGetId($data);
            if($insert_resign){
                Employee::where('id',$request->input('id'))->update(['resign_status'=>1]);
                return redirect()->route('employee.index')->with('success', 'Employee Successfully Resign');
            }else{
                return redirect()->route('employee.index')->with('danger', 'Employee Failed Resign');
            }
        }
    }

    public function CreateAbsen(Request $request)
    {
        // Lakukan pemrosesan untuk menambahkan data baru
        try{
            $code = Auth::user()->employee_code;
            $company = Employee::where('nik', $code)->first();
            // Cek Project
            $schedule = Schedule::where('employee', $request->input('user'))
                                ->where('tanggal', $request->input('tanggal'))
                                ->pluck('project')
                                ->first();
                                
            if ($company->unit_bisnis === 'Kas'){
                if ($schedule === null) {
                    return redirect()->back()->with('error', 'Tidak ada jadwal untuk pengguna ini pada tanggal tersebut');
                }
            }

            $attendance = new Absen;
            $attendance->user_id = $request->input('user');
            $attendance->nik = $request->input('user');
            $attendance->tanggal = $request->input('tanggal');
            $attendance->clock_in = $request->input('clock_in');
            $attendance->clock_out = $request->input('clock_out');
            $attendance->latitude = $request->input('latitude');
            $attendance->longtitude = $request->input('longtitude');
            $attendance->status = $request->input('status');
            $attendance->project = $schedule;
            $attendance->save();
    
            return redirect()->back()->with('success', 'Absensi berhasil ditambahkan');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // Update Absen
    public function UpdateAbsen(Request $request, $date, $nik)
    {
        $code = Auth::user()->employee_code;
        $company = Employee::where('nik', $code)->first();

        // Lakukan pemrosesan untuk mengedit data berdasarkan tanggal ($date) dan nik ($nik)
        $attendance = Absen::where('tanggal', $date)->where('nik', $nik)->first();
        $schedule = Schedule::where('employee', $request->input('user'))
                                ->where('tanggal', $request->input('tanggal'))
                                ->pluck('project')
                                ->first();

        if ($company->unit_bisnis === 'Kas'){
            if ($schedule === null) {
                return redirect()->back()->with('error', 'Tidak ada jadwal untuk pengguna ini pada tanggal tersebut');
            }
        }

        if ($attendance) {
            $attendance->clock_in = $request->input('clock_in');
            $attendance->clock_out = $request->input('clock_out');
            $attendance->status = $request->input('status');
            $attendance->project = $schedule;
            $attendance->save();

            return redirect()->back()->with('success', 'Data Berhasil di ubah');
        } else {
            return redirect()->back()->with('error', 'Data tidak ditemukan untuk tanggal dan nik yang dipilih');
        }
    }

    public function CreateAbsenBackup(Request $request)
    {
        // Lakukan pemrosesan untuk menambahkan data baru
        try{
            // Cek Project
            $schedule = ScheduleBackup::where('employee', $request->input('user'))
                                ->where('tanggal', $request->input('tanggal'))
                                ->pluck('project')
                                ->first();
            
            // cek schedule
            if ($schedule === null) {
                return redirect()->back()->with('error', 'Tidak ada jadwal untuk anggota ini pada tanggal tersebut');
            }

            $attendance = new AbsenBackup;
            $attendance->nik = $request->input('user');
            $attendance->tanggal = $request->input('tanggal');
            $attendance->project = $schedule;
            $attendance->clock_in = $request->input('clock_in');
            $attendance->clock_out = $request->input('clock_out');
            $attendance->latitude = $request->input('latitude');
            $attendance->longtitude = $request->input('longtitude');
            $attendance->status = $request->input('status');
            $attendance->save();
    
            return redirect()->back()->with('success', 'Absensi berhasil ditambahkan');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // Update Absen
    public function UpdateAbsenBackup(Request $request, $date, $nik)
    {
        // Lakukan pemrosesan untuk mengedit data berdasarkan tanggal ($date) dan nik ($nik)
        $attendance = AbsenBackup::where('tanggal', $date)->where('nik', $nik)->first();

        if ($attendance) {
            $attendance->clock_in = $request->input('clock_in');
            $attendance->clock_out = $request->input('clock_out');
            $attendance->status = $request->input('status');
            $attendance->save();

            return redirect()->back()->with('success', 'Data Berhasil di ubah');
        } else {
            return redirect()->back()->with('error', 'Data tidak ditemukan untuk tanggal dan nik yang dipilih');
        }
    }

    // Export Karyawan
    public function exportEmployee()
    {
        // Get Bulan
        $loggedInUserNik = auth()->user()->employee_code;
        $company = Employee::where('nik', $loggedInUserNik)->first();

        // Dapatkan nilai unit bisnis dari request
        $unitBisnis = $company->unit_bisnis;

        // Buat instance dari kelas AttendenceExport dengan rentang waktu
        $export = new EmployeeExport($unitBisnis, $loggedInUserNik);

        // Ekspor data ke Excel
        return Excel::download($export, 'truest_employee_export.xlsx');
    }

    // Import Karyawan
    public function importEmployee(Request $request)
    {
        try {
            $request->validate([
                'csv_file' => 'required|mimes:xlsx,csv,txt',
            ]);
            $data = $request->file('csv_file');

            $namaFIle = $data->getClientOriginalName();
            $data->move('KaryawanImport', $namaFIle);
            Excel::import(new EmployeeImport, \public_path('/KaryawanImport/'.$namaFIle));

            return redirect()->back()->with('success', 'Import berhasil!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Import gagal. ' . $e->getMessage());
        }
    }

    // Tambah Absen
    

    public function MyProfile($nik)
    {
        $employee = Employee::where('nik', $nik)->with('payrolinfo')->first();
        $nikdata = $employee->nik;

        $today = now();
        $startDate = $today->day >= 21 ? $today->copy()->day(21) : $today->copy()->subMonth()->day(21);
        $endDate = $today->day >= 21 ? $today->copy()->addMonth()->day(20) : $today->copy()->day(20);

        // Hitung jumlah hari kerja tanpa absensi (termasuk akhir pekan)
        $totalWorkingDays = $startDate->diffInWeekdays($endDate) + 1;

        // Fetch attendance data for the current month
        $attendanceData = DB::table('absens')
            ->where('nik', $nikdata)
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->orderBy('tanggal', 'asc')
            ->get();

        // Hitung jumlah hari dengan absensi
        $daysWithAttendance = count($attendanceData);

        // Hitung jumlah hari tanpa absensi
        $daysWithoutAttendance = $totalWorkingDays - $daysWithAttendance;

        // No ClockOut
        $daysWithClockInNoClockOut = 0;

        foreach ($attendanceData as $absendata) {
            if (!empty($absendata->clock_in) && empty($absendata->clock_out)) {
                $daysWithClockInNoClockOut++;
            }
        }

        // Sakit
        $sakit = 0;

        foreach ($attendanceData as $absendata) {
            if ($absendata->status == 'Sakit') {
                $sakit++;
            }
        }

        $izin = 0;
        $leaveTotal = 0;

        foreach ($attendanceData as $absendata) {
            if ($absendata->status == 'Izin') {
                $izin++;
            }
        }

        // Request Absen
        $requestAbsen = RequestAbsen::where('employee',$nikdata)
                        ->whereBetween('tanggal', [$startDate, $endDate])
                        ->get();
        
        //count Request
        $CountRequest = RequestAbsen::where('employee',$nikdata)
                        ->whereBetween('tanggal', [$startDate, $endDate])
                        ->count(); 
        
        $leaveTotal = $sakit + $izin;
        return view('pages.user-pages.profile', compact('employee','attendanceData', 'daysWithoutAttendance','daysWithClockInNoClockOut','daysWithAttendance','sakit','requestAbsen','CountRequest','izin','leaveTotal'));
    }

    // generate referal code

    public function unix_code(){
        $unix = $this->generateCodeVisitor("karyawan","id", 4, "CITY");
        dd($unix);
    }

    private function generateCodeVisitor($tbl, $field, $jml, $inisial){
        $unixTimestampNumber = time();
        $unixTimestampString = date('ymdHis', $unixTimestampNumber);

        return "CITY".$unixTimestampString;
    }
}
