<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, $permission)
    {
        if (Auth::check()) {
            // Mengecek izin akses pengguna
            $userPermissions = json_decode(Auth::user()->permission, true);
        
            if (!is_array($userPermissions)) {
                abort(500, 'Invalid user permissions format.');
            }
        
            foreach ($userPermissions as $data) {
                if ($data === $permission) {
                    return $next($request);
                }
            }
        
            abort(403, 'Anda tidak memiliki izin untuk mengakses halaman ini.');
        }        

        return redirect('/login');
    }
}
