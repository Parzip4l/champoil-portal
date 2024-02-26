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
use App\Absen\RequestAbsen;
use App\ModelCG\asign_test;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Mail\PayslipEmail;
use Illuminate\Support\Facades\Mail;
use App\PengajuanSchedule\PengajuanSchedule;
use PDF;

class DashboardController extends Controller
{
    public function index()
    {   
        // Request Approval
        $code = Auth::user()->employee_code;
        $company = Employee::where('nik', $code)->first();
        $today = now();
        $startDate = $today->day >= 21 ? $today->copy()->day(20) : $today->copy()->subMonth()->day(21);
        $endDate = $today->day >= 21 ? $today->copy()->addMonth()->day(20) : $today->copy()->day(20);

        $dataRequest = RequestAbsen::join('karyawan', 'requests_attendence.employee', '=', 'karyawan.nik')
            ->where('karyawan.unit_bisnis', $company->unit_bisnis)
            ->where('aprrove_status', 'Pending')
            ->select('requests_attendence.*', 'karyawan.*')
            ->get();
        
        $pengajuanSchedule = PengajuanSchedule::where('status', 'Ditinjau')->get();

        $dataAbsen = Absen::join('karyawan', 'absens.nik', '=', 'karyawan.nik')
        ->where('karyawan.unit_bisnis', $company->unit_bisnis)
        ->whereBetween('tanggal', [$startDate, $endDate])
        ->select('absens.*', 'karyawan.*')
        ->get();
        

        // Data Absensi 
        $labels = [];
        $currentDate = $startDate->copy();
        while ($currentDate->lte($endDate)) {
            $labels[] = $currentDate->format('Y-m-d');
            $currentDate->addDay();
        }
        // Calculate the total absences for each day
        $dataAbsenByDay = [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Total Absences',
                    'backgroundColor' => '#36a2eb',
                    'data' => [],
                ]
            ]
        ];

        foreach ($labels as $label) {
            $absencesCount = $dataAbsen->where('tanggal', $label)->count();
            $dataAbsenByDay['datasets'][0]['data'][] = $absencesCount;
        }


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


        return view('dashboard', 
        compact(
            'karyawan','alreadyClockIn','alreadyClockOut','isSameDay','datakaryawan','logs','hariini','asign_test','dataRequest','pengajuanSchedule',
            'dataAbsenByDay'
        ));
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
}
