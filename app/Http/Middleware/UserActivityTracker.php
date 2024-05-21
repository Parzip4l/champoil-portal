<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log; // Import Log facade
use App\Activities\Log as ActivityLog;
use App\Employee;

class UserActivityTracker
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {

        Log::info('Middleware UserActivityTracker sedang berjalan');
        $response = $next($request);

        // Lakukan sesuatu setelah request selesai dijalankan

        // Cek apakah pengguna sedang login
        if (Auth::check()) {
            $code = Auth::user()->employee_code;
            $company = Employee::where('nik', $code)->first();
            // Simpan log aktivitas pengguna
            $log = new ActivityLog();
            $log->user_id = $code;
            $log->action = 'Accessed: ' . $request->fullUrl();
            $log->description = $company->unit_bisnis;
            $log->save();
        }

        return $next($request);
    }
}
