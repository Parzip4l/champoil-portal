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
use App\ModelCG\asset\PengajuanCicilan;
use App\ModelCG\asset\BarangCicilan;
use App\Loan\LoanModel;

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

    public function check_nik(Request $request) {
        // Validate the input to ensure NIK is provided
        $request->validate([
            'nik' => 'required|string|max:255',
        ]);
    
        // Fetch the employee data based on NIK
        $employee = Employee::where('nik', $request->input('nik'))->first();
    
        // Check if the employee exists
        if ($employee) {
            // Return success response with the employee data
            return response()->json([
                'status' => 'success',
                'data' => $employee
            ], 200);
        } else {
            // Return failure response if NIK not found
            return response()->json([
                'status' => 'error',
                'message' => 'Employee with the provided NIK not found.'
            ], 404);
        }
    }

    public function pengajuan_hp() {
        
        $pengajuan = PengajuanCicilan::all();
        if($pengajuan){
            foreach($pengajuan as $row){
                $row->nama_lengkap = karyawan_bynik($row->nik)->nama;
                $row->nama_project = project_byID($row->project)->name;
                $row->nama_barang =BarangCicilanDetail($row->barang_diajukan)->nama_barang;
                $row->harga_rupiah =formatRupiah($row->harga);
                $row->tanggal_pengajuan =date('d F Y H:i:s',strtotime($row->created_at));
                if($row->status ==0){
                    $status  = '<span class="badge rounded-pill bg-warning">Pending</span>';
                }else if($row->status ==1){
                    $status  = '<span class="badge rounded-pill bg-success">Approved</span>';
                }else if($row->status ==2){
                    $status  = '<span class="badge rounded-pill bg-danger">Reject</span>';
                }
                $row->status  = $status;
            }
        }

    
        // Check if the employee exists
        if ($pengajuan) {
            // Return success response with the employee data
            return response()->json([
                'status' => 'success',
                'data' => $pengajuan
            ], 200);
        } else {
            // Return failure response if NIK not found
            return response()->json([
                'status' => 'error',
                'message' => 'Employee with the provided NIK not found.'
            ], 404);
        }
    }

    public function update_pengajuan(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:pengajuan_cicilans,id', // Ensure the ID exists in the database
            'status' => 'required|in:1,2' // Ensure the status is either approved or rejected
        ]);
        
        // Find the pengajuan by ID
        $pengajuan = PengajuanCicilan::join('barang_cicilans', 'barang_cicilans.id', '=', 'pengajuan_cicilans.barang_diajukan')
            ->where('pengajuan_cicilans.id', $request->input('id'))
            ->first();
        
        // Check if pengajuan exists
        if (!$pengajuan) {
            return response()->json([
                'status' => 'error',
                'message' => 'Pengajuan not found.'
            ], 404);
        }
        
        // Update the status
        $pengajuan->status = $request->input('status');
        $pengajuan->save(); // Save the changes
        
        // If status is 'approved', perform an additional action
        if ($pengajuan->status == 1) {
            $this->saveToLoan($pengajuan);
        }
        
        // Return a success response
        return response()->json([
            'status' => 'success',
            'message' => 'Pengajuan status updated successfully.'
        ], 200);
    }

    public function submit_pengajuan_cicilan(Request $request) {
        // Validate the request data
        $request->validate([
            'nik' => 'required|string|max:255',
            'project' => 'required|string|max:255',
            'nomor_hp' => 'required|string|max:15',
            'email' => 'required|email|max:255',
            'ktp' => 'required|file|mimes:jpg,png,pdf|max:2048', // Example validation for file
            'barang_diajukan' => 'required|exists:barang_cicilans,id', // Assuming this refers to the BarangnCicilan model
        ]);
    
        // Get the input data
        $data = $request->all();
    
        // Find the price based on the item being applied for
        $cek_harga = BarangCicilan::where('id',$request->input('barang_diajukan'))->first();
    
        // Check if the item exists
        if (!$cek_harga) {
            return response()->json([
                'status' => 'error',
                'message' => 'Item not found.'
            ], 404);
        }
    
        // Handle the file upload for KTP
        if ($request->hasFile('ktp')) {
            $file = $request->file('ktp');
            $filePath = $file->store('uploads/ktp', 'public'); // Store the file in the public disk
        }
    
        // Prepare data for insertion
        $insert = [
            "nik" => $data['nik'],
            "project" => $data['project'],
            "nomor_hp" => $data['nomor_hp'],
            "email" => $data['email'],
            "ktp" => isset($filePath) ? $filePath : null, // Store file path or null if no file
            "barang_diajukan" => $data['barang_diajukan'],
            "harga" => $cek_harga->harga,
            "status"=>0,
            "created_at"=>date('Y-m-d H:i:s')
        ];
    
        // Insert the data into the PengajuanCicilan model
        $pengajuan = PengajuanCicilan::insert($insert); // Assuming `create` is used with fillable fields
    
        // Return success response
        return response()->json([
            'status' => 'success',
            'data' => $pengajuan
        ], 201);
    }

    private function saveToLoan($record){
       

        // Hitung besaran cicilan per bulan
        $installmentAmount = $record->perbulan * 3;

        

        // Buat entri pinjaman
        $loan = new LoanModel();
        $loan->employee_id = $record->nik;
        $loan->amount = $installmentAmount;
        $loan->remaining_amount = $installmentAmount;
        $loan->installments = 3;
        $loan->installment_amount = $record->perbulan; // Use the new variable
        $loan->save();
    }
    
    
}
