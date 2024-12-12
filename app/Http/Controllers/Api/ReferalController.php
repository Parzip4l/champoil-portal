<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\PersonalAccessToken;


//model
use App\Employee;
use App\ModelCG\JobApplpicant;
use App\ModelCG\Penempatan;
use App\ModelCG\Referal;


class ReferalController extends Controller
{
    public function search_referal(){
        $records = Employee::where('unit_bisnis', 'Kas')
            ->whereIn('organisasi', ['FRONTLINE OFFICER', 'Frontline Officer'])
            ->get();

        $data = [];

        if ($records->isNotEmpty()) {
            foreach ($records as $employee) {
                // Fetch applicants linked to the employee's referral code
                $applicants = JobApplpicant::where('kode_referal', $employee->referal_code)->get();

                if ($applicants->isNotEmpty()) {
                    $referals = $applicants->map(function ($applicant) {
                        // Fetch placement history
                        $placement = Penempatan::select('tanggal_penempatan', 'status', 'id_user')
                            ->where('status', 'Penempatan')
                            ->where('id_user', $applicant->id)
                            ->first();

                        if(!empty($placement)){
                            Referal::insert(['referal_code'=>$applicant->kode_referal,'nik'=>$applicant->nomor_induk,'status'=>0,'recruitments_id'=>$applicant->id]);
                        }
                        

                        return [
                            'id' => $applicant->id,
                            'nama_lengkap' => $applicant->nama_lengkap,
                            'kode_referal' => $applicant->kode_referal,
                            'status' => $placement->status ?? null,
                            'tanggal_penempatan' => $placement->tanggal_penempatan ?? null,
                        ];
                    });

                    $data[] = [
                        'nama' => $employee->nama,
                        'referal_code' => $employee->referal_code,
                        'data_referals' => $referals,
                    ];
                }
            }

            $msg = "Data found";
            $error = false;
        } else {
            $msg = "No data found";
            $error = true;
        }

        return response()->json([
            'msg' => $msg,
            'error' => $error,
            'records' => $data,
        ], 200);
    }

    public function referal_data(Request $request){
        try {
            // Authenticate the user
            $user = Auth::guard('api')->user();
            
            if (!$user) {
                return response()->json(['error' => 'Unauthorized access'], 401);
            }

            // return response()->json($user, 200);
        
            // Use the correct identifier for employee lookup (nik is assumed here)
            $employeeCode = $user->employee_code;
        
            // Retrieve the unit business of the authenticated user
            $unitBisnis = Employee::where('nik', $employeeCode)->value('unit_bisnis');
        
            if (!$unitBisnis) {
                return response()->json(['error' => 'Unit business not found'], 404);
            }
        
            // Fetch paginated records based on the unit business
            $records = Employee::where('unit_bisnis', $unitBisnis)
                                ->whereIn('organisasi', ['FRONTLINE OFFICER', 'Frontline Officer'])
                                ->where('resign_status',0)
                                ->get(); // Paginate the results to improve performance
            if($records){
                foreach($records as $row){
                    $row->referal_used = Referal::where('referal_code',$row->referal_code)->count();
                    $row->referal_paid = Referal::where('referal_code',$row->referal_code)->where('status',1)->count();
                    $row->referal_resign = Referal::where('referals.referal_code',$row->referal_code)
                                                  ->join('karyawan','karyawan.nik','=','referals.nik')
                                                  ->where('resign_status',1)
                                                  ->count();
                }
            }
        
            // Return a structured response
            return response()->json([
                'success' => true,
                'data' => $records,
                'message' => 'Records fetched successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error retrieving data: ' . $e->getMessage()
            ], 500);
        }
        
    }



}
