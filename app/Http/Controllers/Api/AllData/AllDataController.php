<?php

namespace App\Http\Controllers\Api\AllData;

use App\Http\Controllers\Controller;
// Modul
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\PersonalAccessToken;
use Carbon\Carbon;

// Model
use App\Employee;
use App\User;
use App\Pengumuman\Pengumuman;
use App\News\News;

class AllDataController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function ListPengumuman(Request $request)
    {
        try {
            // Retrieve the token from the request
            $token = $request->bearerToken();
            // Authenticate the user based on the token
            $user = Auth::guard('api')->user();

            $employeeCode = $user->name;
            $unitBisnis = Employee::where('nik', $employeeCode)->value('unit_bisnis');

            $organisasiUser = Employee::where('nik', $employeeCode)->value('organisasi');
            $tanggal_sekarang = now()->format('Y-m-d');
            $pengumuman = Pengumuman::where('end_date', '>=', $tanggal_sekarang)
                            ->where(function ($query) use ($organisasiUser) {
                                $query->where('tujuan', $organisasiUser)
                                    ->orWhere('tujuan', 'semua');
                            })
                            ->get();

            return response()->json(['dataPengumuman' => $pengumuman], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error updating request: ' . $e->getMessage()], 500);
        }
    }

    public function showPengumuman($id)
    {
        try {
            // Retrieve the token from the request
            $token = request()->bearerToken();
            // Authenticate the user based on the token
            $user = Auth::guard('api')->user();

            $employeeCode = $user->name;
            $unitBisnis = Employee::where('nik', $employeeCode)->value('unit_bisnis');

            // Cari pengumuman berdasarkan ID dan perusahaan (company)
            $pengumuman = Pengumuman::where('id', $id)
                                    ->where('company', $unitBisnis)
                                    ->first();

            if (!$pengumuman) {
                return response()->json(['error' => 'Pengumuman tidak ditemukan'], 404);
            }

            return response()->json(['dataPengumuman' => $pengumuman], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    public function ListBerita(Request $request)
    {
        try {
            // Retrieve the token from the request
            $token = $request->bearerToken();
            // Authenticate the user based on the token
            $user = Auth::guard('api')->user();

            $employeeCode = $user->name;
            $unitBisnis = Employee::where('nik', $employeeCode)->value('unit_bisnis');
            $tanggal_sekarang = now()->format('Y-m-d');
            $berita = News::where('company', $unitBisnis)->get();

            return response()->json(['dataBerita' => $berita], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error updating request: ' . $e->getMessage()], 500);
        }   
    }

    public function showBerita($id)
    {
        try {
            // Retrieve the token from the request
            $token = request()->bearerToken();
            // Authenticate the user based on the token
            $user = Auth::guard('api')->user();

            $employeeCode = $user->name;
            $unitBisnis = Employee::where('nik', $employeeCode)->value('unit_bisnis');

            // Cari berita berdasarkan ID dan perusahaan (company)
            $berita = News::where('id', $id)
                        ->where('company', $unitBisnis)
                        ->first();

            if (!$berita) {
                return response()->json(['error' => 'Berita tidak ditemukan'], 404);
            }

            return response()->json(['dataBerita' => $berita], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
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
