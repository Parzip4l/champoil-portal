<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Purchase;
use App\Sales;
use App\Invoice;
use Carbon\Carbon;
use App\Absen;
use App\Employee;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class DashboardController extends Controller
{
    public function index()
    {   
        // Get Years & Month Now
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;
        
        // Get last Years & Month 
        $lastMonth = Carbon::now()->subMonth()->month;
        $lastYear = Carbon::now()->subMonth()->year;

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
                $karyawanData = compact('karyawan');
        
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
        
        return view('dashboard', compact('totalPembelianBulanIni', 'totalPembelianBulanLalu', 'percentageChange', 'changeMessage', 'arrowIcon', 'textClass','salesData',
            'salesData2', 'TotalSales', 'TotalSalesLatest','PersentaseSales','arrowIcon2', 'textClass2', 'YearlySales', 'changeMessage2','greeting','karyawan','alreadyClockIn','alreadyClockOut','isSameDay','datakaryawan','logs','hariini'
        ));
    }

    public function getSalesData()
    {
        
    }
}
