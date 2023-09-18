<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Purchase;
use Carbon\Carbon;

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
                $changeMessage = 'Lebih tinggi';
                $arrowIcon = 'arrow-up';
                $textClass = 'text-success';
            } else if ($percentageChange < 0) {
                $changeMessage = 'Lebih rendah';
                $arrowIcon = 'arrow-down';
                $textClass = 'text-danger';
            } else {
                $changeMessage = 'Tidak ada perubahan';
                $arrowIcon = 'arrow-right';
                $textClass = 'text-secondary';
            }
        } else {
            $percentageChange = null;
            $changeMessage = 'Total pembelian bulan lalu adalah 0';
            $arrowIcon = 'minus';
            $textClass = 'text-muted';
        }

        // Ambil data penjualan perhari bulan ini
        $salesData = Purchase::whereMonth('created_at', now()->month)
                             ->selectRaw('date(created_at) as date, sum(total) as total_sales')
                             ->groupBy('date')
                             ->get();
        
        return view('dashboard', compact('totalPembelianBulanIni', 'totalPembelianBulanLalu', 'percentageChange', 'changeMessage', 'arrowIcon', 'textClass','salesData'));
    }

    public function getSalesData()
    {
        
    }
}
