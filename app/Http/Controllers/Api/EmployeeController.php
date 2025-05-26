<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\EmployeeResign;
use App\Koperasi\Anggota;
use App\Absen;
use App\ModelCG\Project;
use App\Employee;
use Illuminate\Support\Facades\DB;


class EmployeeController extends Controller
{
    // resign kurang dari 30 hari
    public function resign(){
        $result=[];

        $records = EmployeeResign::all();
        if($records){
            foreach($records as $row){
                $row->project = "";
                $last_schdl = Absen::where('nik', $row->employee_code)->first();

                if($last_schdl){
                    $project = Project::where('id', $last_schdl->project)->first();
                    $row->project = $project->name??"-";
                }

                // Tanggal awal
                $tanggal_awal = date('Y-m-d',strtotime($row->join_date));

                // Tanggal akhir
                $tanggal_akhir = date('Y-m-d',strtotime($row->created_at));

                // Konversi tanggal ke timestamp
                $timestamp_awal = strtotime($tanggal_awal);
                $timestamp_akhir = strtotime($tanggal_akhir);

                // Hitung selisih timestamp
                $selisih_hari = ($timestamp_akhir - $timestamp_awal) / (60 * 60 * 24);
                if($selisih_hari <= 30){
                    $result[]=$row;
                }
                
            }
        }

        return response()->json([
            'error'=>false,
            'result'=>$result    
        ]);
    }

    public function lastAbsen(Request  $request){

        $result = [];

        $company="Kas";
        // Ambil semua data karyawan
        $employees = Employee::where('unit_bisnis',$company)->get();

        foreach ($employees as $employee) {
            // Hitung jumlah absen dalam 90 hari terakhir
            $absenCount = Absen::where('nik', $employee->nik)
                ->where('tanggal', '>=', Carbon::now()->subDays(90)) // Filter tanggal
                ->count();
            if($absenCount==0 && $employee->jabatan != "CLIENT" && $employee->organisasi != "Management Leaders" && $employee->organisasi != "MANAGEMENT LEADERS"){
                $result[] = [
                    'nik' => $employee->nik,
                    'nama' => $employee->nama,
                    'total_absen' => $absenCount,
                ];
                $cekAnggota = Anggota::where('employee_code', $employee->nik)->first(); 
                $data = [
                    "employee_code" => $employee->nik,
                    "nama" => $employee->nama,
                    "ktp" => $employee->ktp,
                    "join_date" => $employee->joindate,
                    "meta_karyawan" => json_encode($employee),
                    "created_at" => date('Y-m-d H:i:s'),
                    "unit_bisnis" => $employee->unit_bisnis
                ];
        
                // Insert data resign
                $insert_resign = EmployeeResign::insertGetId($data);
        
                if ($insert_resign) {
                    // Update status resign karyawan
                    Employee::where('id', $employee->id)->update(['resign_status' => 1]);
        
                    // Cek apakah karyawan adalah anggota koperasi
                    if ($cekAnggota) { // Misalnya, is_member adalah kolom yang menunjukkan keanggotaan koperasi
                        // Ubah status anggota koperasi menjadi tidak aktif
                        Anggota::where('employee_code', $employee->nik)->update(['member_status' => 'deactive']);
                    }
        
                }
            }
            
        }

        return response()->json([
            'error' => false,
            'result' => $result
        ]); 
    }

    public function all_employee(){
        $records = Employee::where('resign_status',0)->get();
        $html='*Important Update for Truest App!*

Hello esteemed Truest users,
        
We would like to inform you that a critical update for the Truest app is now available. Please follow these steps promptly:
        
Google Play Store (Android):
https://play.google.com/store/apps/details?id=co.id.truest.truest
        
App Store (iOS):
https://apps.apple.com/idn/app/truest/id6476389232?platform=iphone
        
Thank you for your attention and cooperation. If you have any questions or issues, please feel free to contact our support team.';
        foreach($records as $row){
            push_notif_wa($html,'','',$row->telepon,'');
        }
        
        


        $result=[
            "records"=>$records,
            "message"=>"success",
            "error"=>false
        ];

        return response()->json($result);

    }

    public function turnover_statistik(){

        $year = date('Y');
        $month=[1,2,3,4,5,6,7,8,9,10,11,12];

        $records=[];

        foreach($month as $key=>$val){
            $records[] = EmployeeResign::whereYear('created_at', $year)
                                        ->whereMonth('created_at', $val)
                                        ->where('unit_bisnis','=','Kas')
                                        ->count();
        }

        

        $result=[
            "records"=>json_encode($records),
            "message"=>"success",
            "error"=>false
        ];

        return response()->json($result);
    }

    public function unResign($id){
        $records = Employee::where('id',$id)->update(['resign_status'=>0]);
        $result=[
            "records"=>$records,
            "message"=>"success",
            "error"=>false
        ];

        return response()->json($result);
    }

    public function EmployeeUpdate(Request $request){
        $records = Employee::where('id',$request->id)->first();
        if($records){
            $records->nama = $request->nama;
            $records->alamat = $request->alamat;
            $records->telepon = $request->telepon;
            $records->status_pernikahan = $request->status_pernikahan;
            $records->tanggungan = $request->tanggungan;
            $records->sertifikasi = $request->sertifikasi;
            $records->expired_sertifikasi = $request->sertifikasi_expired_date;
            $records->pendidikan_trakhir = $request->pendidikan;

            Employee::where('id',$request->id)->update([
                'nama' => $records->nama,
                'alamat' => $records->alamat,
                'telepon' => $records->telepon,
                'telepon_darurat' => str_replace('-','',$request->nomor_telepon_darurat),
                'status_pernikahan' => str_replace('-','',$records->status_pernikahan),
                'tanggungan' => $records->tanggungan,
                'sertifikasi' => $records->sertifikasi,
                'expired_sertifikasi' => $records->expired_sertifikasi,
                'pendidikan_trakhir' => $records->pendidikan_trakhir
            ]);

            $payroll = DB::table('payrolinfos')->where('employee_code',$records->nik)->first();
            $data_payrol = [
                // 'bpjs_tk' => $request->bpjs_kesehatan,
                'npwp' => $request->npwp,
                'bank_name' => $request->bank_name,
                'bank_number' => $request->nomor_rekening,
            ];
            
            if($payroll){
                DB::table('payrolinfos')->where('employee_code',$records->nik)->update($data_payrol);
            }else{
                $data_payrol['employee_code'] = $records->nik;
                DB::table('payrolinfos')->insert($data_payrol);
            }

            if ($request->hasFile('foto_biru')) {
                $file = $request->file('foto_biru');
            
                // Simpan file ke folder public/uploads
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('uploads'), $filename);
            
                // Simpan path ke DB
                $photoPath = 'uploads/' . $filename;
            } else {
                $photoPath = null;
            }
            
            DB::table('karyawan_update_rutin')->insert([
                "document_type"=>"update",
                "user_id"=>$request->id,
                "created_at"=>date('Y-m-d H:i:s'),
                "bb"=>$request->berat_badan,
                "tb"=>$request->tinggi_badan,
                "golongan_darah"=>$request->golongan_darah,
                "photo_biru"=>$photoPath]);

            if($records){
                return response()->json([
                    'error'=>false,
                    'result'=>$records    
                ]);
            }
        }
        $result=[
            "records"=>[],
            "message"=>"success",
            "error"=>false
        ];

        return response()->json($result);
    }
}
