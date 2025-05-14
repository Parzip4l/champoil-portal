<?php

namespace App\Http\Controllers\Payrol;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use PDF;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;


// Model
use App\Employee;
use App\Payrol;
use App\PayrolCM;
use App\PayrolComponent_NS;
use App\Component\ComponentDetails;
use App\Component\ComponentMaster;
use App\Absen;
use App\Payrollns;
use App\Pajak\Pajak;
use App\Pajak\PajakDetails;
use Illuminate\Support\Facades\DB;
Use App\Activities\Log;
use App\Mail\PayslipEmail;
use App\Absen\RequestAbsen;
use App\Exports\PayrollAbsensiExport;

use App\Imports\PayrollImport;
use Maatwebsite\Excel\Facades\Excel;



class PayrolController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $code = Auth::user()->employee_code;
        $employee = Employee::where('nik', $code)->first();

        if ($employee) {
            $unit_bisnis = $employee->unit_bisnis;
        
            // Mengambil data Payroll berdasarkan unit bisnis dari tabel Employee
            $payrol = PayrolCM::join('karyawan', 'payrol_components.employee_code', '=', 'karyawan.nik')
                ->where('karyawan.unit_bisnis', $unit_bisnis)
                ->where('karyawan.resign_status','0')
                ->get();
        } else {
            $payrol = [];
        }

        return view('pages.hc.payrol.payrol', compact('payrol'));
    }

    public function indexns()
    {
        $code = Auth::user()->employee_code;
        $employee = Employee::where('nik', $code)->first();

        $payrol = PayrolComponent_NS::all();

        return view('pages.hc.payrol.ns.payrol', compact('payrol'));
    }

    public function checkAttendance(Request $request)
    {
        $start_date = $request->start_date;
        $end_date = $request->end_date;

        // Ambil awal dan akhir bulan dari start_date yang dipilih
        $firstDayOfMonth = \Carbon\Carbon::parse($start_date)->startOfMonth()->toDateString();
        $lastDayOfMonth = \Carbon\Carbon::parse($start_date)->endOfMonth()->toDateString();

        // Ambil semua karyawan yang ada di payroll
        $payrol = PayrolComponent_NS::join('karyawan', 'payrol_component_ns.employee_code', '=', 'karyawan.nik')
                    ->where('karyawan.unit_bisnis', 'CHAMPOIL')
                    ->where('karyawan.resign_status', 0)
                    ->select('payrol_component_ns.*') 
                    ->get();

        $data = [];

        foreach ($payrol as $item) {
            $nik = $item->employee_code;

            // Total hari kerja dalam minggu yang dipilih
            $totalHariKerjaMingguan = \Carbon\Carbon::parse($start_date)->diffInDays(\Carbon\Carbon::parse($end_date)) + 1;

            // Total kehadiran dalam minggu yang dipilih
            $totalHadirMingguan = Absen::join('karyawan', 'absens.user_id', '=', 'karyawan.nik')
                ->where('karyawan.unit_bisnis', 'CHAMPOIL')
                ->where('karyawan.nik', $nik) 
                ->whereBetween('absens.tanggal', [$start_date, $end_date])
                ->where('status', 'H')
                ->count();

            // Total hari kerja dalam satu bulan penuh
            $totalHariKerjaBulanan = 0;
            $currentDate = \Carbon\Carbon::parse($firstDayOfMonth);

            while ($currentDate->lte(\Carbon\Carbon::parse($lastDayOfMonth))) {
                if ($currentDate->isWeekday()) { // Hanya hitung Senin - Jumat
                    $totalHariKerjaBulanan++;
                }
                $currentDate->addDay();
            }

            // Total kehadiran dalam satu bulan penuh
            $totalHadirBulanan = Absen::join('karyawan', 'absens.user_id', '=', 'karyawan.nik')
                ->where('karyawan.unit_bisnis', 'CHAMPOIL')
                ->where('karyawan.nik', $nik) 
                ->whereBetween('absens.tanggal', [$firstDayOfMonth, $lastDayOfMonth])
                ->count();

            // Hitung total jam lembur dalam minggu yang dipilih
            $totalLembur = (int) RequestAbsen::where('employee', $nik)
                ->whereBetween('tanggal', [$start_date, $end_date])
                ->where('aprrove_status', 'Approved')
                ->sum('alasan');

            // Hitung hari bolos dalam 1 bulan
            $totalBolosBulanan = $totalHariKerjaBulanan - $totalHadirBulanan;

            // Tentukan uang kerajinan (jika bolos <= 3 hari dalam sebulan, maka dapat uang kerajinan
            $uangKerajinan = ($totalBolosBulanan <= 3) ? 200000 : 0;

            $data[$nik] = [
                'employee_code' => $nik,
                'uang_makan' => $totalHadirMingguan == $totalHariKerjaMingguan - 2 ? 50000 : 0,
                'total_lembur' => $totalLembur ?? 0,
                'total_hari_kerja' => $totalHadirMingguan,
                'uang_kerajinan' => $uangKerajinan
            ];
        }

        return response()->json(['data' => $data]);
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
        $code = Auth::user()->employee_code;
        $employee = Employee::where('nik', $code)->first();

        $unit_bisnis = $employee->unit_bisnis;
        $employeeCodes = $request->input('employee_code');
        $bulan = $request->input('month');
        $tahun = $request->input('year');

        $pdfPaths = []; // Array to hold the paths of the generated PDFs

        try {
            // Begin database transaction
            DB::beginTransaction();

            // Loop through each employee code
            foreach ($employeeCodes as $code) {
                $payrollComponents = PayrolCM::where('employee_code', $code)->first();
                $additionalAllowances = ComponentDetails::where('employee_code', $code)
                                        ->where('type', 'Allowances')
                                        ->get();

                $additionalDeductions = ComponentDetails::where('employee_code', $code)
                                        ->where('type', 'Deductions')
                                        ->get();

                // Initialize arrays to store additional data
                $additionalDataArray = [];
                $deductionDataArray = [];
                $additionalAllowanceTotal = 0;
                $additionalDeductionTotal = 0;

                // Loop through additional allowances
                foreach ($additionalAllowances as $additionalData) {
                    $componentName = $additionalData->component_name;
                    $nominal = $additionalData->nominal;
                    // Add additional data to the array
                    $additionalAllowanceTotal += $nominal;
                    $additionalDataArray[$componentName] = $nominal;
                }

                // Loop through additional deductions
                foreach ($additionalDeductions as $additionalDataDeductions) {
                    $componentName = $additionalDataDeductions->component_name;
                    $nominal = $additionalDataDeductions->nominal;
                    // Add additional data to the array
                    $additionalDeductionTotal += $nominal;
                    $deductionDataArray[$componentName] = $nominal;
                }

                if ($payrollComponents) {
                    // Get payroll component details
                    $basic_salary = $payrollComponents->basic_salary;
                    $allowancesData = $payrollComponents->allowances;
                    $deductionsData = $payrollComponents->deductions;
                    $NetSalary = $payrollComponents->net_salary;
                    $allowancesArray = json_decode($allowancesData, true);
                    $deductionArray = json_decode($deductionsData, true);

                    // Ensure 'total_allowance' and 'total_deduction' are initialized as arrays
                    if (!is_array($allowancesArray)) {
                        $allowancesArray = ['total_allowance' => 0];
                    } else if (!isset($allowancesArray['total_allowance'])) {
                        $allowancesArray['total_allowance'] = 0;
                    }

                    if (!is_array($deductionArray)) {
                        $deductionArray = ['total_deduction' => 0];
                    } else if (!isset($deductionArray['total_deduction'])) {
                        $deductionArray['total_deduction'] = 0;
                    }

                    // Add additional totals to existing totals
                    $allowancesArray['total_allowance'] += $additionalAllowanceTotal;
                    $deductionArray['total_deduction'] += $additionalDeductionTotal;

                    // Merge additional data with existing arrays
                    $allowancesArray = array_merge($allowancesArray, $additionalDataArray);
                    $deductionArray = array_merge($deductionArray, $deductionDataArray);

                    // Convert arrays back to JSON
                    $newAllowancesData = json_encode($allowancesArray);
                    $newDeductionsData = json_encode($deductionArray);

                    $netSalary = $NetSalary + $additionalAllowanceTotal - $additionalDeductionTotal;

                    // Save payroll data
                    $payroll = new Payrol();
                    $payroll->employee_code = $code;
                    $payroll->month = $bulan;
                    $payroll->year = $tahun;
                    $payroll->basic_salary = $basic_salary;
                    $payroll->allowances = $newAllowancesData;
                    $payroll->deductions = $newDeductionsData;
                    $payroll->net_salary = $netSalary;
                    $payroll->payrol_status = 'Unlocked';
                    $payroll->payslip_status = 'Unpublish';
                    $payroll->unit_bisnis = $unit_bisnis;
                    $payroll->run_by = $employee->nama;
                    $payroll->save();
                }
            }

            // Send the email with the payslip PDF attachment to each user
            

            // Commit the transaction
            DB::commit();

            return redirect()->route('payslip.showByMonth', ['month' => $bulan, 'year' => $tahun])->with('success', 'Payroll successfully created');
        } catch (\Exception $e) {
            // Rollback the transaction in case of any exception
            DB::rollback();
            // Log the error
            \Log::error('Error creating payroll: ' . $e->getMessage());
            // Redirect back with error message
            return redirect()->back()->with(['error' => 'Error creating payroll. Please try again.' . $e->getMessage()]);
        }
    }


    public function storens(Request $request)
    {
        $code = Auth::user()->employee_code;
        $employee = Employee::where('nik', $code)->first();

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
                if($totalAbsen > 5) {
                    $totalAbsen = 5;
                }
                // Hitung total daily salary
                $daily_salary = $payrollComponents->daily_salary;
                $totaldaily = $daily_salary * ($totalAbsen);
                // Hitung total potongan
                $dataDeductions = json_decode($payrollComponents->deductions, true);
                $totalPotongan = $dataDeductions['hutang'][0] + $dataDeductions['mess'][0] + $dataDeductions['lain_lain'][0];
    
                // Hitung THP (Take Home Pay)
                $thpdetails = ($totaldaily + $jamLemburdata + $uang_makan[$index] + $uang_kerajinan[$index]) - $totalPotongan;
    
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
                $payroll->payrol_status = 'Unlocked';
                $payroll->payslip_status = 'Unpublish';
                $payroll->run_by = $employee->nama;
                $payroll->company = $employee->unit_bisnis;
                $payroll->save();
            }
        }
    
        return redirect()->route('payroll.ns')->with(['success' => 'Data Berhasil Disimpan!']);
    }
    
    
    public function importns(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
            'month' => 'required|string',
            'week' => 'required|string',
            'year' => 'required|integer',
        ]);

        // Passing the period data to the import class
        Excel::import(new PayrollImport($request->month, $request->week, $request->year), $request->file('file'));

        return redirect()->back()->with('success', 'Payroll data imported successfully.');
    }

    // CSV

    public function downloadExcel($periode)
    {
        return Excel::download(new PayrollAbsensiExport($periode), "Payroll_Absensi_{$periode}.xlsx");
    }
}
