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
    $startDate = Carbon::createFromDate(null, $selectedMonth, 1)->startOfWeek(Carbon::MONDAY);
    $endDate = Carbon::createFromDate(null, $selectedMonth, 1)->endOfMonth()->endOfWeek(Carbon::FRIDAY);

    // Inisialisasi array untuk menyimpan daftar minggu
    $weeks = [];

    // Perulangan untuk mengisi array dengan daftar minggu
    $currentDate = $startDate;
    $weekNumber = 1;

    while ($currentDate->lte($endDate)) {
        $weekStart = $currentDate->format('Y-m-d'); // Format tanggal start
        $weekEnd = $currentDate->copy()->addDays(4)->format('Y-m-d'); // Format tanggal end

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
        
        $employeeCodes = $request->input('employee_code');
        $bulan = $request->input('month');
        $tahun = $request->input('year');
        $week = $request->input('week');

        // Extract Week
        list($startDate, $endDate) = explode(' - ', $week);

        $payrolldetails = [];
        $payrollComponents = PayrolComponent_NS::whereIn('employee_code', $employeeCodes)->get();
        $tambahanData = json_decode($payrollComponents, true);

        for ($i = 0; $i < count($request->employee_code); $i++) {
            $employeeId = $request->employee_code[$i];
            $jamLembur = $request->lembur_jam[$i];
            $uangMakan = $request->uang_makan[$i];
            $uangKerajinan = $request->uang_kerajinan[$i];

            $lemburAllowance = 0;
            foreach ($payrollComponents as $component) {
                if ($component['employee_code'] === $employeeId) {
                    // Dapatkan nilai lembur dari allowances
                    $allowancesData = json_decode($component['allowances'], true);
                    if (isset($allowancesData['lembur'][0])) {
                        $lemburAllowance = $allowancesData['lembur'][0];
                    }
                    break; // Keluar dari loop setelah nilai lembur ditemukan
                }
            }

            // Mengalikan jam_lembur dengan nilai lembur dari allowances
            $jamLemburdata = $jamLembur * $lemburAllowance;

            $payrolldetails[] = [
                'employee_code' => $employeeId,
                'jam_lembur' => $jamLembur,
                'uang_makan' => $uangMakan,
                'uang_kerajinan' => $uangKerajinan
            ];
        }
        // Loop melalui setiap employee code
        foreach ($employeeCodes as $code) {
            $payrollComponents = PayrolComponent_NS::where('employee_code', $code)->first();
            if ($payrollComponents) {
                // Simpan detail payroll component
                $daily_salary = $payrollComponents->daily_salary;
                $allowancesData = $payrollComponents->allowances;
                $deductionsData = $payrollComponents->deductions;
                $lembur = $payrollComponents->lembur;

                // Get Absen 
                $totalAbsen = Absen::where('nik', $code)
                                ->whereBetween('tanggal', [$startDate, $endDate])
                                ->count();

                $totalAbsenPerEmployee[$code] = $totalAbsen;
                // Simpan data payroll
                $payroll = new Payrol();
                $payroll->employee_code = $code;
                $payroll->month = $bulan;
                $payroll->year = $tahun;
                $payroll->week = $week;
                $payroll->basic_salary = $daily_salary * $totalAbsen;
                $payroll->allowances = $allowancesData;
                $payroll->deductions = $deductionsData;
                $payroll->net_salary = $jamLemburdata;
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
