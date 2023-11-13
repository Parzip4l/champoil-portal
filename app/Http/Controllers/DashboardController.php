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
use PDF;

class DashboardController extends Controller
{
    public function index()
    {   
        // Request Approval
        $code = Auth::user()->employee_code;
        $company = Employee::where('nik', $code)->first();

<<<<<<< HEAD
        // Get Total Purchase Now
        $totalPembelianBulanIni = Purchase::whereMonth('created_at', $currentMonth)
                                 ->whereYear('created_at', $currentYear)
                                 ->sum('total');

        $totalPembelianBulanLalu = Purchase::whereMonth('created_at', $lastMonth)
        ->whereYear('created_at', $lastYear)
        ->sum('total');

        // Persentase Pembelian
        if ($totalPembelianBulanLalu != 0) {
            $percentageChange = (($totalPembelianBulanIni - $totalPembelianBulanLalu) / $totalPembelianBulanLalu) * 100;
        
            // Tentukan pesan dan tanda panah berdasarkan perubahan
            if ($percentageChange > 0) {
                $changeMessage = 'Lebih Ringgi Dari Bulan Lalu';
                $arrowIcon = 'arrow-up';
                $textClass = 'text-success';
            } else if ($percentageChange < 0) {
                $changeMessage = 'Lebih Rendah Dari Bulan Lalu';
                $arrowIcon = 'arrow-down';
                $textClass = 'text-danger';
            } else {
                $changeMessage = 'Tidak ada perubahan';
                $arrowIcon = 'arrow-right';
                $textClass = 'text-secondary';
            }
        } else {
            $percentageChange = null;
            $changeMessage = 'Tidak ada pembelian bulan lalu ';
            $arrowIcon = 'minus';
            $textClass = 'text-muted';
        }

        // Ambil data Pembelian perhari bulan ini
        $salesData = Purchase::whereMonth('created_at', now()->month)
                             ->selectRaw('date(created_at) as date, sum(total) as total_sales')
                             ->groupBy('date')
                             ->get();

        // Orderan Data
        $TotalSales = Invoice::whereMonth('created_at', $currentMonth)
                                 ->whereYear('created_at', $currentYear)
                                 ->sum('total');

        $TotalSalesLatest = Invoice::whereMonth('created_at', $lastMonth)
        ->whereYear('created_at', $lastYear)
        ->sum('total');


        if ($TotalSalesLatest != 0) {
            $PersentaseSales = (($TotalSales - $TotalSalesLatest) / $TotalSalesLatest) * 100;
        
            // Tentukan pesan dan tanda panah berdasarkan perubahan
            if ($PersentaseSales > 0) {
                $arrowIcon2 = 'arrow-up';
                $textClass2 = 'text-success';
                $changeMessage2 = 'Lebih Tinggi Dari Bulan Lalu';
            } else if ($PersentaseSales < 0) {
                $arrowIcon2 = 'arrow-down';
                $textClass2 = 'text-danger';
                $changeMessage2 = 'Lebih Rendah Dari Bulan Lalu';
            } else {
                $arrowIcon2 = 'arrow-right';
                $textClass2 = 'text-secondary';
                $changeMessage2 = 'Tidak Ada Perubahan';
            }
        } else {
            $PersentaseSales = null;
            $arrowIcon2 = 'minus';
            $textClass2 = 'text-muted';
            $changeMessage2="";
        }

        $salesData2 = Invoice::whereMonth('created_at', now()->month)
                             ->selectRaw('date(created_at) as date2, sum(total) as total_sales2')
                             ->groupBy('date2')
                             ->get();
        
        $YearlySales = Invoice::whereYear('created_at', now()->year)
                            ->selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, sum(total) as total_sales')
                            ->groupBy('year', 'month')
                            ->get();

        // Greeting
=======
        $dataRequest = RequestAbsen::join('karyawan', 'requests_attendence.employee', '=', 'karyawan.nik')
            ->where('karyawan.unit_bisnis', $company->unit_bisnis)
            ->where('aprrove_status', 'Pending')
            ->select('requests_attendence.*', 'karyawan.*')
            ->get();
>>>>>>> ea975c674fc0170e26e661dd1f286d107cdc17b7

        date_default_timezone_set('Asia/Jakarta'); // Set timezone sesuai dengan lokasi Anda
            $hour = date('H'); // Ambil jam saat ini

            if ($hour >= 5 && $hour < 12) {
                $greeting = 'Selamat Pagi';
            } else if ($hour >= 12 && $hour < 18) {
                $greeting = 'Selamat Siang';
            } else {
                $greeting = 'Selamat Malam';
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


        return view('dashboard', compact('greeting','karyawan','alreadyClockIn','alreadyClockOut','isSameDay','datakaryawan','logs','hariini','asign_test','dataRequest'
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
