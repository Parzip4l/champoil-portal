<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Cache;
use App\Employee;

class LoginController extends Controller
{
    public function index() {
        if($user = Auth::user()){
            return redirect()->intended('dashboard');
        }
        return view('pages.auth.login');
    }

    public function proses(Request $request)
    {
        
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);
        $cek = Employee::where('email', $request->email)->where('resign_status',0)->first();
        if(!$cek){
            Session::flash('error', 'Email ada berada d  employee resign.');
            return redirect()->back()->withInput();
        }

        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $token = $request->user()->createToken('authToken')->accessToken;
            session(['barrier' => $token]);
            // Simpan token dalam cache server selama satu jam
            Cache::put('nik' . $request->user()->name, $token, 250000);
            return redirect()->intended('dashboard')->with('token', $token);
        } else {
            // Jika login gagal, tambahkan notifikasi ke flash session
            Session::flash('error', 'Email atau password salah.');
            return redirect()->back()->withInput();
        }
    }  

    public function logout(Request $request)
    {
        Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

        return redirect('/');
    }
}
