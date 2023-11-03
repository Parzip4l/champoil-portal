<?php

namespace App\Http\Controllers\Payrol;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Employee;
use App\Payrol;
use App\PayrolCM;
use App\PayrolComponent_NS;
use Carbon\Carbon;
use App\Absen;
use App\Payrollns;
class PayrolController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $payrol = PayrolCM::all();
        return view('pages.hc.payrol.payrol', compact('payrol'));
    }

    public function indexns()
    {
        $payrol = PayrolComponent_NS::all();
        return view('pages.hc.payrol.ns.payrol', compact('payrol'));
    }

    public function getWeeks(Request $request)
    {
        $monthNames = [
            'Januari' => 1,
            'Februari' => 2,
            'Maret' => 3,
            'April' => 4,
            'Mei' => 5,
            'Juni' => 6,
            'Juli' => 7,
            'Agustus' => 8,
            'September' => 9,
            'Oktober' => 10,
            'November' => 11,
            'Desember' => 12
        ];
        $selectedMonth = $monthNames[$request->input('month')];

        // Menggunakan Carbon untuk mendapatkan tanggal awal dan akhir dari bulan
        $startDate = Carbon::createFromDate(null, $selectedMonth, 1)->startOfWeek(Carbon::SATURDAY);
        $endDate = Carbon::createFromDate(null, $selectedMonth, 1)->endOfMonth()->endOfWeek(Carbon::FRIDAY);

        // Inisialisasi array untuk menyimpan daftar minggu
        $weeks = [];

        // Perulangan untuk mengisi array dengan daftar minggu
        $currentDate = $startDate;
        $weekNumber = 1;

        while ($currentDate->lte($endDate)) {
            $weekStart = $currentDate->format('Y-m-d'); // Format tanggal start
            $weekEnd = $currentDate->copy()->addDays(6)->format('Y-m-d'); // Format tanggal end

            $weeks[] = "Week " . $weekNumber . " ($weekStart - $weekEnd)";
            $currentDate->addWeek();
            $weekNumber++;
        }

        return response()->json(['weeks' => $weeks]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $employeeCodes = $request->input('employee_code');
        $bulan = $request->input('month');
        $tahun = $request->input('year');

        // Loop melalui setiap employee code
        foreach ($employeeCodes as $code) {
            $payrollComponents = PayrolCM::where('employee_code', $code)->first();

            if ($payrollComponents) {
                // Simpan detail payroll component
                $basic_salary = $payrollComponents->basic_salary;
                $allowancesData = $payrollComponents->allowances;
                $deductionsData = $payrollComponents->deductions;
                $NetSalary = $payrollComponents->net_salary;

                // Simpan data payroll
                $payroll = new Payrol();
                $payroll->employee_code = $code;
                $payroll->month = $bulan;
                $payroll->year = $tahun;
                $payroll->basic_salary = $basic_salary;
                $payroll->allowances = $allowancesData;
                $payroll->deductions = $deductionsData;
                $payroll->net_salary = $NetSalary;
                $payroll->save();
            }
        }

        return redirect()->route('payroll.index')->with(['success' => 'Data Berhasil Disimpan!']);
    }

    public function storens(Request $request)
    {
        // Mendapatkan input dari request
        $employeeCodes = $request->input('employee_code');
        $lembur_jam = $request->input('lembur_jam');
        $uang_makan = $request->input('uang_makan');
        $uang_kerajinan = $request->input('uang_kerajinan');
        $bulan = $request->input('month');
        $tahun = $request->input('year');
        $week = $request->input('week');
    
        // Extract Week
        list($startDate, $endDate) = explode(' - ', $week);
    
        // Inisialisasi array untuk menyimpan detail payroll
        $payrolldetails = [];
    
        // Loop melalui setiap employee code
        foreach ($employeeCodes as $index => $employeeCode) {
            // Mengalikan jam_lembur dengan nilai lembur dari allowances
            $lemburAllowance = 0; // Inisialisasi nilai lembur allowance-
    
            // Simpan detail payroll
            $payrolldetails[] = [
                'employee_code' => $employeeCode,
                'jam_lembur' => $lembur_jam[$index],
                'uang_makan' => $uang_makan[$index],
                'uang_kerajinan' => $uang_kerajinan[$index]
            ];
    
            // Dapatkan data payroll components berdasarkan employee code
            $payrollComponents = PayrolComponent_NS::where('employee_code', $employeeCode)->first();
    
            if ($payrollComponents) {
                $allowancesData = json_decode($payrollComponents->allowances, true);
                $lemburAllowance = $allowancesData['lembur'][0];
                $jamLemburdata = $lembur_jam[$index] * $lemburAllowance;
                // Simpan data absensi
                $totalAbsen = Absen::where('nik', $employeeCode)
                    ->whereBetween('tanggal', [$startDate, $endDate])
                    ->count();
    
                // Hitung total daily salary
                $daily_salary = $payrollComponents->daily_salary;
                $totaldaily = $totalAbsen * $daily_salary;
    
                // Hitung total potongan
                $dataDeductions = json_decode($payrollComponents->deductions, true);
                $totalPotongan = $dataDeductions['hutang'][0] + $dataDeductions['mess'][0] + $dataDeductions['lain_lain'][0];
    
                // Hitung THP (Take Home Pay)
                $thpdetails = $totaldaily + $jamLemburdata + $uang_makan[$index] + $uang_kerajinan[$index] - $totalPotongan;
    
                // Simpan data payroll
                $payroll = new Payrollns();
                $payroll->employee_code = $employeeCode;
                $payroll->month = $bulan;
                $payroll->year = $tahun;
                $payroll->periode = $week;
                $payroll->daily_salary = $daily_salary;
                $payroll->total_absen = $totalAbsen;
                $payroll->lembur_salary = $lemburAllowance;
                $payroll->jam_lembur = $lembur_jam[$index];
                $payroll->total_lembur = $jamLemburdata;
                $payroll->uang_makan = $uang_makan[$index];
                $payroll->uang_kerajinan = $uang_kerajinan[$index];
                $payroll->potongan_hutang = $dataDeductions['hutang'][0];
                $payroll->potongan_mess = $dataDeductions['mess'][0];
                $payroll->potongan_lain = $dataDeductions['lain_lain'][0];
                $payroll->thp = $thpdetails;
                $payroll->total_daily = $totaldaily;
                $payroll->save();
            }
        }
    
        return redirect()->route('payroll.ns')->with(['success' => 'Data Berhasil Disimpan!']);
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
