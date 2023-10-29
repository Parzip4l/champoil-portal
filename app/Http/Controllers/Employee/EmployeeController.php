<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Employee;
use App\Absen;
use App\UserActivities;
use App\ModelCG\Jabatan;
use Carbon\Carbon;
use App\Absen\RequestAbsen;
use Illuminate\Support\Facades\DB;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $karyawan = Employee::all();
        return view('pages.hc.karyawan.index', compact('karyawan'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {   
        $jabatan = Jabatan::all();
        return view('pages.hc.karyawan.create', compact('jabatan'));
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
            $request->validate([
                'nama' => 'required',
                'gambar' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
            ]);
        
            $data = new Employee();
            $data->ktp = $request->ktp;
            $data->nik = $request->nik;
            $data->nama = $request->nama;
            $data->alamat = $request->alamat;
            $data->jabatan = $request->jabatan;
            $data->organisasi = $request->organisasi;
            $data->status_kontrak = $request->status_kontrak;
            $data->joindate = $request->joindate;
            $data->berakhirkontrak = $request->berakhirkontrak;
            $data->email = $request->email;
            $data->telepon = $request->telepon;
            $data->status_pernikahan = $request->status_pernikahan;
            $data->agama = $request->agama;
            $data->tanggal_lahir = $request->tanggal_lahir;
            $data->tempat_lahir = $request->tempat_lahir;
            $data->jenis_kelamin = $request->jenis_kelamin;
            $data->tanggungan = $request->tanggungan;

            if ($request->hasFile('gambar')) {
                $image = $request->file('gambar');
                $filename = time() . '.' . $image->getClientOriginalExtension();
                $destinationPath = public_path('/images');
                $image->move($destinationPath, $filename);
                $data->gambar = $filename;
            }
            $data->save();

            return redirect()->route('employee.index')->with(['success' => 'Data Berhasil Disimpan!']);
        }catch (ValidationException $exception) {
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
        $employee = Employee::find($id);
        return view('pages.hc.karyawan.edit', compact('employee'));
    }

    public function getAttendanceData(Request $request) {
        $selectedMonth = $request->input('month');
        $selectedYear = $request->input('year');

        // Hitung tanggal awal (start_date) dan tanggal akhir (end_date) berdasarkan bulan dan tahun yang dipilih
        $start_date = Carbon::create($selectedYear, $selectedMonth, 21, 0, 0, 0);
        $end_date = $start_date->copy()->addMonth()->day(20);

        // Buat array yang akan berisi data untuk setiap tanggal dalam rentang
        $tableData = [];

        // Loop melalui setiap tanggal dalam rentang
        $currentDate = $start_date->copy();
        while ($currentDate->lte($end_date)) {
            // Cari data absen untuk tanggal saat ini
            $attendanceData = Absen::whereDate('tanggal', $currentDate)->first();

            // Buat array data untuk tanggal ini
            $rowData = [
                'tanggal' => $currentDate->format('Y-m-d'),
                'clock_in' => $attendanceData ? $attendanceData->clock_in : '-',
                'clock_out' => $attendanceData ? $attendanceData->clock_out : '-',
                'status' => $attendanceData ? $attendanceData->status : '-',
            ];

            // Tambahkan kolom tombol Edit jika diperlukan
            $rowData['edit_button'] = true;

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
        $request->validate([
            'nama' => 'required|string|max:255',
            'ktp' => 'required|numeric',
            'nik' => 'required|string|max:255',
            'jabatan' => 'required|string|max:255',
            'agama' => 'required|string|max:255',
            'jenis_kelamin' => 'required|string|in:Laki-Laki,Perempuan',
            'email' => 'required|email',
            'telepon' => 'required|string|max:15',
            'status_kontrak' => 'required|string|in:Contract,Permanent',
            'organisasi' => 'required|string|in:Professional Frontline,Management Leaders',
            'joindate' => 'required|date',
            'berakhirkontrak' => 'required|date',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'alamat' => 'required|string',
            'status_pernikahan' => 'required|string|in:Married,Single',
        ]);

        // Find the employee by ID
        $employee = Employee::findOrFail($id);

        // Update the employee data
        $employee->nama = $request->input('nama');
        $employee->ktp = $request->input('ktp');
        $employee->nik = $request->input('nik');
        $employee->jabatan = $request->input('jabatan');
        $employee->agama = $request->input('agama');
        $employee->jenis_kelamin = $request->input('jenis_kelamin');
        $employee->email = $request->input('email');
        $employee->telepon = $request->input('telepon');
        $employee->status_kontrak = $request->input('status_kontrak');
        $employee->organisasi = $request->input('organisasi');
        $employee->joindate = $request->input('joindate');
        $employee->berakhirkontrak = $request->input('berakhirkontrak');
        $employee->tempat_lahir = $request->input('tempat_lahir');
        $employee->tanggal_lahir = $request->input('tanggal_lahir');
        $employee->alamat = $request->input('alamat');
        $employee->status_pernikahan = $request->input('status_pernikahan');
        $employee->tanggungan = $request->input('tanggungan');

        // Save the updated employee
        $employee->save();

        // Redirect to a view or return a response as needed
        return redirect()->route('employee.index')->with('success', 'Employee data updated successfully.');
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
        $contact->delete();
        return redirect()->route('employee.index')->with('success', 'Employee Successfully Deleted');
    }

    // Update Absen
    public function UpdateAbsen(Request $request, $date)
    {
        // Lakukan pemrosesan untuk mengedit data berdasarkan tanggal ($date)
        // Misalnya, simpan perubahan pada database
        $attendance = Absen::where('tanggal', $date)->first();
        if ($attendance) {
            $attendance->clock_in = $request->input('clock_in');
            $attendance->clock_out = $request->input('clock_out');
            $attendance->status = $request->input('status');
            $attendance->save();

            return redirect()->back()->with('success', 'Data Absensi berhasil diubah');
        } else {
            return redirect()->back()->with('error', 'Data tidak ditemukan untuk tanggal yang dipilih');
        }
    }

    // Tambah Absen
    public function CreateAbsen(Request $request)
    {
        // Lakukan pemrosesan untuk menambahkan data baru
        // Misalnya, simpan data baru ke database
        try{
            $attendance = new Absen;
            $attendance->user_id = $request->input('user');
            $attendance->nik = $request->input('user');
            $attendance->tanggal = $request->input('tanggal');
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
}
