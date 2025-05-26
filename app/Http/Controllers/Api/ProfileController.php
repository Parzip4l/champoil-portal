<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
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


// models
use App\Employee;
use App\User;
use App\Company\CompanyModel;
use App\Absen\RequestType;
use App\Payrolinfo\Payrolinfo;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $error=true;
        $msg="Data Empty";

        $token = $request->bearerToken();
        // Authenticate the user based on the token
        $user = Auth::guard('api')->user();

        if($user){
            $error=false;
            $msg="Success";
        }

        $result=[
            "msg"=>$msg,
            "error"=>$error,
            "user"=>$user
        ];
        
        return response()->json($result, 200);
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
    public function update(Request $request)
    {

        $error=true;
        $msg="Data Empty";

        $token = $request->bearerToken();
        // Authenticate the user based on the token
        $user = Auth::guard('api')->user();

        try {
            $request->validate([
                'nama' => 'required|string|max:255',
                'ktp' => 'required|numeric',
            ]);
            $code = $user->employee_code;
            $company = Employee::where('nik', $code)->first();
            DB::beginTransaction();
            // Find the employee by ID
            $employee = Employee::where('nik', $code)->first();
            
            // Kembalikan jika tidak ditemukan
            if (!$employee) {
                $msg='Employee not found.';
                $error=true;
            }
            // Update the employee data
            $employee->nama = $request->input('nama');
            $employee->ktp = $request->input('ktp');
            $employee->nik = $request->input('nik');
            $employee->jabatan = $request->input('jabatan');
            $employee->agama = $request->input('agama');
            $employee->jenis_kelamin = $request->input('jenis_kelamin');
            $employee->email = $request->input('email');
            $employee->telepon = $request->input('telepon');
            $employee->status_kontrak = $request->input('status_kontrak');
            $employee->organisasi = $request->input('organisasi');
            $employee->joindate = $request->input('joindate');
            $employee->berakhirkontrak = $request->input('berakhirkontrak');
            $employee->tempat_lahir = $request->input('tempat_lahir');
            $employee->tanggal_lahir = $request->input('tanggal_lahir');
            $employee->alamat = $request->input('alamat');
            $employee->status_pernikahan = $request->input('status_pernikahan');
            $employee->tanggungan = $request->input('tanggungan');

            // Update the employee's photo if a new one is provided
            if ($request->hasFile('gambar')) {
                $photo = $request->file('gambar');
                $photoFileName = time() . '.' . $photo->getClientOriginalExtension();
                $photo->move(public_path('/images'), $photoFileName);
                
                // Delete old photo if exists
                if ($employee->photo) {
                    $oldPhotoPath = public_path('/images') . $employee->photo;
                    if (file_exists($oldPhotoPath)) {
                        unlink($oldPhotoPath);
                    }
                }
                $employee->gambar = $photoFileName;
            }
            
            // Save the updated employee
            $employee->save();
            
            // Cek Payrol Info
            $payrollInfo = Payrolinfo::where('employee_code', $code)->first();

            // Payroll Info 
            if ($payrollInfo) {
                $payrollInfo->employee_code = $request->input('nik');
                $payrollInfo->bpjs_kes = $request->input('bpjs_kes');
                $payrollInfo->bpjs_tk = $request->input('bpjs_tk');
                $payrollInfo->npwp = $request->input('npwp');
                $payrollInfo->bank_name = $request->input('bank_name');
                $payrollInfo->bank_number = $request->input('bank_number');
                $payrollInfo->ptkp = $request->input('tanggungan');
                $payrollInfo->save();
            } else {
                // Jika tidak ditemukan maka buat baru
                $payrolinfo = new Payrolinfo();
                $payrolinfo->employee_code = $request->nik;
                $payrolinfo->bpjs_kes = $request->bpjs_kes;
                $payrolinfo->bpjs_tk = $request->bpjs_tk;
                $payrolinfo->npwp = $request->npwp;
                $payrolinfo->bank_name = $request->bank_name;
                $payrolinfo->bank_number = $request->bank_number;
                $payrolinfo->ptkp = $request->tanggungan;
                $payrolinfo->save();
            }

            $userInfo = User::where('name', $code)->first();

            if (!$userInfo) {
                $userInfo = new User();
                $userInfo->name = $request->nik;
                $userInfo->email = $request->email;
                $userInfo->password = Hash::make($request->password);
                $userInfo->permission = json_encode($request->permissions);
                $userInfo->employee_code = $request->nik;
                $userInfo->company = $company->unit_bisnis;
                $userInfo->save();
            }

            DB::commit();
            // Redirect to a view or return a response as needed
            $msg='Employee data updated successfully.';
            $error=false;
        }catch (ValidationException $exception) {
            DB::rollBack();
            $errorMessage = $exception->validator->errors()->first(); // ambil pesan error pertama dari validator
            // if (!$employee->save()) {
            //     $msg='Gagal menyimpan data karyawan.';
            //     $error=true;
            // }
            $error=true;
            // if (!$payrollInfo->save()) {
            //     return redirect()->back()->with('error', '' . $errorMessage);
            //     $msg='Gagal menyimpan data Payroll Info.';
            //     $error=true;
            // }
        }

        $result=[
            "msg"=>$msg,
            "error"=>$error
        ];



        return response()->json($result, 200);
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
