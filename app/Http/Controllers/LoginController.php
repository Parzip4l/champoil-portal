<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Cache;

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

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $user = Auth::user();
            if (!$user) {
                return redirect()->back()->withErrors(['error' => 'User not found after login.']);
            }

            $token = $user->createToken('authToken')->accessToken;
            session(['barrier' => $token]);

            Cache::put('nik_' . $user->nik, $token, now()->addMinutes(60));

            return redirect()->intended('dashboard')->with('token', $token);
        }

        return redirect()->back()->withInput()->with('error', 'Email atau password salah.');
    }


    public function logout(Request $request)
    {
        Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

        return redirect('/');
    }
}
