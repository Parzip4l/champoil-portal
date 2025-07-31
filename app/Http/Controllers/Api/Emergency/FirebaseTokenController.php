<?php

namespace App\Http\Controllers\Api\Emergency;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\FirebaseToken;
use Illuminate\Support\Facades\Auth;

class FirebaseTokenController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function store(Request $request)
    {
        $request->validate([
            'token' => 'required|string',
        ]);

        $user = Auth::user(); // Mendapatkan pengguna yang terautentikasi


        FirebaseToken::where('user_id', $user->id)->delete(); // Hapus token lama jika ada

       
        FirebaseToken::updateOrCreate(
            ['user_id' => $user->id], 
            ['token' => $request->token] 
        );

        return response()->json(['message' => 'Token berhasil disimpan'], 200);
    }
}
