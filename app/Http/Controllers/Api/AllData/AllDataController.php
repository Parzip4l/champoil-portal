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
use App\ModelCG\Birthday;

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
                            ->where('company',$unitBisnis)
                            ->get();
            if(!empty($pengumuman)){
                foreach($pengumuman as $row){
                    $row->attachments = asset($row->attachments);
                }
            }
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
            $berita->featuredimage = url('images/featuredimage/' . $berita->featuredimage);
            return response()->json(['dataBerita' => $berita], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }
    
    public function BirtdayList(Request $request)
    {
        try {
            $token = request()->bearerToken();
            // Authenticate the user based on the token
            $user = Auth::guard('api')->user();

            $employeeCode = $user->name;
            $today = now();

            $unitBisnis = Employee::where('nik', $employeeCode)->value('unit_bisnis');

            $birthdays = Employee::where('unit_bisnis', $unitBisnis)
                     ->select('tanggal_lahir','nama','ktp','slack_id')
                     ->get();
                     
            $upcomingBirthdays = $birthdays->filter(function ($employee) use ($today) {
                $birthDate = Carbon::parse($employee->tanggal_lahir)->setYear($today->year);
                $employee->usia = Carbon::parse($employee->tanggal_lahir)->age;
                
                return $birthDate->isToday() || ($birthDate->isAfter($today) && $birthDate->diffInDays($today) <= 1);
            });

            if(!empty($upcomingBirthdays->values())){
                foreach($upcomingBirthdays->values() as $row){
                    $cek = Birthday::where('nik',$row->ktp)->count();
                    if($cek === 0){
                        Birthday::insert(['nik'=>$row->ktp]);
                    }
                    
                }
            }

            if (!empty($upcomingBirthdays->values())) {
                foreach ($upcomingBirthdays->values() as $row) {
                    // Check if 'ktp' is not equal to a specific value
                        // Find the birthday record based on 'nik'
                        $record = Birthday::where('nik', $row->ktp)->first();
                        
                        // Proceed if a record is found
                        if (!empty($record)) {
                            // Check if today is the birthday
                            if (date('d') == date('d', strtotime($row->tanggal_lahir))) {
                                $umur = $row->usia;
            
                                // Check if slack_id and messages are not empty
                                if (!empty($row->slack_id) && !empty($record->messages)) {
                                
                                    $message ='{
                                        "blocks": [
                                            {
                                                "type": "section",
                                                "text": {
                                                    "type": "mrkdwn",
                                                    "text": "Selamat Ulang Tahun yang Ke-' . $umur . ', <@' . $row->slack_id . '>!"
                                                }
                                            },
                                            {
                                                "type": "section",
                                                "text": {
                                                    "type": "mrkdwn",
                                                    "text": "'.str_replace(["\r", "\n"], '', $record->messages).'"
                                                }
                                            }
                                        ]
                                    }';
                                    
                                    // Example of pushing message to Slack
                                    // Uncomment the following line to actually send the message
                                //    echo  push_slack_message('https://hooks.slack.com/services/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',$message);
                                $url='https://hooks.slack.com/services/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';   
                                $curl = curl_init($url);
                                   curl_setopt($curl, CURLOPT_URL, $url);
                                   curl_setopt($curl, CURLOPT_POST, true);
                                   curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                                   
                                   $headers = array(
                                       "Content-Type: application/json",
                                   );
                                   curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
                                   
                                   
                                   
                                   curl_setopt($curl, CURLOPT_POSTFIELDS, $message);
                                   
                                   //for debug only!
                                   curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
                                   curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
                                   
                                   $resp = curl_exec($curl);
                                   curl_close($curl);
                                   echo $resp;
                                    // For testing, output the message
                                    // echo $text;
                                }
                            }
                        }
                }
            }
            

            
            

            return response()->json(['EmployeeBirthday' =>$upcomingBirthdays->values()], 200);
  
        } catch (\Exception $e) {
            return response()->json(['error' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }
}
