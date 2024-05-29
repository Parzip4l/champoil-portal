<?php

namespace App\Http\Controllers\Api\Analytics;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
Use App\Activities\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class AnalyticsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    private function getUniqueVisitorsCount()
    {
        try {
            // Ambil data logs hanya untuk bulan ini
            $currentLogs = Log::whereYear('created_at', Carbon::now()->year)
                              ->whereMonth('created_at', Carbon::now()->month)
                              ->get();
    
            // Hitung jumlah pengunjung unik dan jumlah tampilan halaman untuk bulan ini
            $currentVisitorCount = $currentLogs->unique('ip_address')->count();
            $currentPageViewCount = $currentLogs->count();
    
            // Ambil data logs untuk bulan sebelumnya
            $previousLogs = Log::whereYear('created_at', Carbon::now()->subMonth()->year)
                               ->whereMonth('created_at', Carbon::now()->subMonth()->month)
                               ->get();
    
            // Hitung jumlah pengunjung unik dan jumlah tampilan halaman untuk bulan sebelumnya
            $previousVisitorCount = $previousLogs->unique('ip_address')->count();
            $previousPageViewCount = $previousLogs->count();
    
            // Hitung persentase perubahan untuk jumlah pengunjung unik dan jumlah tampilan halaman
            $visitorCountChange = $currentVisitorCount - $previousVisitorCount;
            $visitorCountChangePercentage = $previousVisitorCount != 0 ? ($visitorCountChange / $previousVisitorCount) * 100 : 0;
    
            $pageViewCountChange = $currentPageViewCount - $previousPageViewCount;
            $pageViewCountChangePercentage = $previousPageViewCount != 0 ? ($pageViewCountChange / $previousPageViewCount) * 100 : 0;
    
            // Format data dalam format JSON
            return response()->json([
                'current_unique_visitor_count' => $currentVisitorCount,
                'current_page_view_count' => $currentPageViewCount,
                'previous_unique_visitor_count' => $previousVisitorCount,
                'previous_page_view_count' => $previousPageViewCount,
                'visitor_count_change' => $visitorCountChange,
                'visitor_count_change_percentage' => round($visitorCountChangePercentage, 2),
                'page_view_count_change' => $pageViewCountChange,
                'page_view_count_change_percentage' => round($pageViewCountChangePercentage, 2),
            ]);
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
            return [
                'current_unique_visitor_count' => 0,
                'current_page_view_count' => 0,
                'previous_unique_visitor_count' => 0,
                'previous_page_view_count' => 0,
                'visitor_count_change' => 0,
                'visitor_count_change_percentage' => 0,
                'page_view_count_change' => 0,
                'page_view_count_change_percentage' => 0,
            ]; // Return 0 if there's an error
        }
    }
    

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
