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
    protected $excludedUrls = [
        'activities', // URL yang akan dikecualikan dari pencatatan aktivitas
    ];

    public function handle(Request $request, Closure $next)
    {
        if (!$this->isExcludedUrl($request->url())) {
            // Lakukan sesuatu sebelum request dijalankan
            $requestedUrl = $request->fullUrl();

            // Panggil controller yang dijalankan
            $response = $next($request);

            // Lakukan sesuatu setelah request selesai dijalankan
            // Cek apakah pengguna sedang login
            if (Auth::check()) {
                $code = Auth::user()->employee_code;
                $company = Employee::where('nik', $code)->first();

                // Dapatkan informasi route
                $route = $request->route();
                $controller = 'Unknown';
                $method = 'Unknown';

                if ($route) {
                    $controllerAction = $route->getActionName();

                    // Pisahkan controller dan method dari nama action
                    if (strpos($controllerAction, '@') !== false) {
                        [$controller, $method] = explode('@', $controllerAction);
                    } else {
                        $controller = $controllerAction;
                    }
                }

                // Simpan log aktivitas pengguna
                $ipAddress = $request->ip();
                $log = new ActivityLog();
                $log->user_id = $code;
                $log->action = 'URL: ' . $requestedUrl;
                $log->controller = substr($controller . '@' . $method, 0, 255);
                $log->ip_address = $ipAddress;
                $log->description = $company->unit_bisnis;
                $log->save();
            }
        } else {
            // Jika URL termasuk dalam daftar URL yang dikecualikan, lanjutkan ke controller tanpa melakukan logging
            $response = $next($request);
        }

        return $response;
    }

    protected function isExcludedUrl($url)
    {
        foreach ($this->excludedUrls as $excludedUrl) {
            if (strpos($url, $excludedUrl) !== false) {
                return true;
            }
        }

        return false;
    }
}
