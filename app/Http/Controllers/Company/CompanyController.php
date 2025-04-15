<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\NewCompanyAdmin;

// Model
use App\Company\CompanyModel;
use App\Company\CompanySetupChecklist;
use App\Employee;
use App\Setting\Features\FeaturesModel;
use App\Setting\Features\CompanyFeatures;
use App\Company\CompanySetting;
use App\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;


class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $company = Auth::user()->company;
        $dataLogin = json_decode(Auth::user()->permission);
        
        if(in_array('superadmin_access', $dataLogin))
        {
            $companyData = CompanyModel::all();
        } elseif(in_array('hc_access', $dataLogin)) {
            $companyData = CompanyModel::where('company_name', $company)->get();
        } else {
            $companyData = CompanyModel::where('company_name', $company)->get();
        }

        return view('pages.company.index',compact('companyData'));
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

    public function editmenu($id)
    {
        try {
            $company = CompanyModel::findOrFail($id);
            $features = FeaturesModel::where('is_active',1)->get();

            // Menggunakan company_name sebagai company_id
            $enabledFeatures = CompanyFeatures::where('company_id', $company->company_name)
                ->where('is_enabled', 1)
                ->pluck('feature_id')
                ->toArray();

            return view('pages.company.menuset', compact('company', 'features', 'enabledFeatures'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Data tidak ditemukan: ' . $e->getMessage());
        }
    }


    public function toggleFeature(Request $request)
    {
        try {
            $request->validate([
                'company_id' => 'required|exists:company,company_name', // Validasi menggunakan company_name
                'feature_id' => 'required|exists:features,id',
                'is_enabled' => 'required|boolean'
            ]);

            $companyName = $request->company_id; // Ambil company_name dari request
            $featureId = $request->feature_id;
            $isEnabled = $request->is_enabled;

            // Periksa apakah fitur sudah ada untuk perusahaan ini
            $companyFeature = CompanyFeatures::where('company_id', $companyName)
                ->where('feature_id', $featureId)
                ->first();

            if ($companyFeature) {
                // Update status fitur
                $companyFeature->update(['is_enabled' => $isEnabled]);
            } else {
                // Buat fitur baru jika belum ada
                CompanyFeatures::create([
                    'company_id' => $companyName, // Gunakan nama perusahaan sebagai company_id
                    'feature_id' => $featureId,
                    'is_enabled' => $isEnabled
                ]);
            }

            return response()->json(['message' => 'Feature updated successfully.']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error updating feature: ' . $e->getMessage()], 500);
        }
    }

    public function bulkToggle(Request $request)
    {
        try {
            $request->validate([
                'feature_ids' => 'required|array',
                'is_enabled' => 'required|boolean',
                'company_id' => 'required|exists:company,company_name', // Validasi menggunakan company_name
            ]);

            $companyName = $request->company_id;
            $featureIds = $request->feature_ids;
            $status = $request->is_enabled;

            foreach ($featureIds as $featureId) {
                $companyFeature = CompanyFeatures::where('company_id', $companyName)
                    ->where('feature_id', $featureId)
                    ->first();

                if ($companyFeature) {
                    // Jika fitur sudah ada, update statusnya
                    $companyFeature->update(['is_enabled' => $status]);
                } else {
                    // Jika fitur belum ada, buat entri baru
                    CompanyFeatures::create([
                        'company_id' => $companyName, // Gunakan company_name sebagai company_id
                        'feature_id' => $featureId,
                        'is_enabled' => $status
                    ]);
                }
            }

            return response()->json(['message' => 'Selected features updated successfully.']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error updating features: ' . $e->getMessage()], 500);
        }
    }


    function generateRandomCode($length = 6) {
        $characters = '0123456789';
        $code = '';

        for ($i = 0; $i < $length; $i++) {
            $code .= $characters[rand(0, strlen($characters) - 1)];
        }

        return $code;
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'company_name' => 'required|string|max:255',
            'company_address' => 'required|string',
            'email' => 'required|email|unique:users,email',
        ]);

        DB::beginTransaction();

        try {
            // 1. Simpan data company
            $company = new CompanyModel();
            $company->company_code = $this->generateRandomCode();
            $company->company_name = $request->input('company_name');
            $company->company_address = $request->input('company_address');

            // 2. Upload logo / fallback dummy
            if ($request->hasFile('logo')) {
                $logo = $request->file('logo');
                $filename = time() . '.' . $logo->getClientOriginalExtension();
                $logo->move(public_path('/images/company_logo'), $filename);
            } else {
                $filename = 'default-logo.png'; // pastikan file ini tersedia di folder public/images/company_logo
            }
            $company->logo = $filename;
            $company->save();

            $companyId = $company->id;

            $defaultTitles = ['Dashboard', 'App Setting'];
            $defaultFeatures = FeaturesModel::whereIn('title', $defaultTitles)->get();

            // Default Features
            foreach ($defaultFeatures as $feature) {
                CompanyFeatures::create([
                    'company_id' => $company->company_name,
                    'feature_id' => $feature->id,
                    'is_enabled' => true
                ]);
            }

            // 3. Buat user administrator untuk company tersebut
            $user = new User();
            $user->name = $company->company_code;
            $user->email = $request->input('email');
            $password = Str::random(8);
            $user->password = Hash::make($password);
            $user->employee_code = $company->company_code;
            $user->company = $company->company_name;
            $user->permission = json_encode(['hc_access','dashboard_access']);
            $user->save();

            if (!$user->save()) {
                throw new \Exception('Gagal menyimpan user administrator.');
            }

            // Employee Data

            $employee = new Employee();
            $employee->nama = $company->company_name . ' Admin';
            $employee->ktp = $company->company_code;
            $employee->nik = $company->company_code;
            $employee->referal_code = null;
            $employee->alamat_ktp = null;
            $employee->alamat = $company->company_address;
            $employee->divisi = 'Administrasi';
            $employee->pendidikan_trakhir = 'S1';
            $employee->jurusan = 'Manajemen';
            $employee->sertifikasi = null;
            $employee->expired_sertifikasi = null;
            $employee->telepon_darurat = null;
            $employee->jabatan = 'Administrator';
            $employee->organisasi = $company->company_name;
            $employee->status_kontrak = 'permanent';
            $employee->joindate = now();
            $employee->berakhirkontrak = null;
            $employee->email = $user->email;
            $employee->telepon = '0';
            $employee->status_pernikahan = 'belum menikah';
            $employee->tanggungan = 0;
            $employee->agama = 'Islam';
            $employee->tanggal_lahir = '1990-01-01';
            $employee->tempat_lahir = 'Jakarta';
            $employee->jenis_kelamin = 'Laki-laki';
            $employee->unit_bisnis = $company->company_name;
            $employee->manager = null;
            $employee->slack_id = null;

            $employee->save();

            $defaultSettings = [
                // General
                ['category' => 'general', 'key' => 'timezone', 'value' => 'Asia/Jakarta'],
                ['category' => 'general', 'key' => 'language', 'value' => 'id'],
            
                // Absensi
                ['category' => 'absensi', 'key' => 'absensi_mode', 'value' => 'gps'],
                ['category' => 'absensi', 'key' => 'gps_radius', 'value' => '0.5'], // dalam KM
                ['category' => 'absensi', 'key' => 'default_jam_masuk', 'value' => '08:00'],
                ['category' => 'absensi', 'key' => 'default_jam_pulang', 'value' => '17:00'],
                ['category' => 'absensi', 'key' => 'toleransi_keterlambatan', 'value' => '10'], // menit
                ['category' => 'absensi', 'key' => 'toleransi_pulang_cepat', 'value' => '10'],
            
                // Cuti
                ['category' => 'cuti', 'key' => 'kuota_cuti_tahunan', 'value' => '12'],
                ['category' => 'cuti', 'key' => 'cuti_bisa_akumulasi', 'value' => 'yes'],
                ['category' => 'cuti', 'key' => 'cuti_bisa_konversi', 'value' => 'no'],
            
                // Payroll
                ['category' => 'payroll', 'key' => 'payroll_type', 'value' => 'monthly'],
                ['category' => 'payroll', 'key' => 'pph21_mode', 'value' => 'nett'],
            
                // Hari Kerja
                ['category' => 'absensi', 'key' => 'hari_kerja', 'value' => json_encode(['Senin','Selasa','Rabu','Kamis','Jumat'])],
            ];
            

            // 4. Buat default company setting
            foreach ($defaultSettings as $setting) {
                CompanySetting::create([
                    'company_id' => $companyId,
                    'key' => $setting['key'],
                    'value' => $setting['value'],
                    'updated_by' => $user->employee_code,
                ]);
            }

            // Checklist Company
            foreach (CompanySetupChecklist::defaultSteps() as $key => $label) {
                CompanySetupChecklist::create([
                    'company_code' => $company->company_code,
                    'key' => $key,
                    'is_completed' => false,
                ]);
            }

            // 5. Kirim email ke admin baru
            if ($user && $company) {
                \Log::info('User and company OK', ['user' => $user, 'company' => $company]);
                Mail::to($user->email)->send(new NewCompanyAdmin($user, $company, $password));
            }

            // 6. Tambahkan notifikasi setup awal
            // Notification::create([
            //     'title' => 'Setup Awal Dibutuhkan',
            //     'description' => 'Lengkapi data perusahaan ' . $company->company_name,
            //     'type' => 'setup',
            //     'company_code' => $company->company_code,
            // ]);

            DB::commit();

            return redirect()->route('company.index')->with('success', 'Company berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menyimpan data: ' . $e->getMessage());
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $company = CompanyModel::findOrFail($id);
            $employeeTotal = Employee::where('unit_bisnis',$company->company_name)->count();
            return view('pages.company.show', compact('company','employeeTotal'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Data tidak ditemukan: ' . $e->getMessage());
        }
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
        try {
            // Validate the form data
            $validatedData = $request->validate([
                'company_name' => 'required|string|max:255',
                'company_address' => 'nullable|string',
                'use_scedule' => 'required|in:Yes,No',
                'schedule_type' => 'required|in:Daily,Monthly,No',
                'cutoff_start' => 'required',
                'cutoff_end' => 'required',
                'latitude' => 'required|string',
                'longitude' => 'required|string',
                'radius' => 'required|numeric'
            ]);

            // Handle file upload
            if ($request->hasFile('logo')) {
                $image = $request->file('logo');
                $filename = time() . '.' . $image->getClientOriginalExtension();
                $destinationPath = public_path('/images/company_logo');
                $image->move($destinationPath, $filename);
                $validatedData['logo'] = $filename;
            }

            // Update the company data
            $company = CompanyModel::findOrFail($id);
            $company->update($validatedData);

            // Redirect back with a success message
            return redirect()->route('company.index')->with('success', 'Company updated successfully');
        } catch (\Exception $e) {
            // Handle exceptions, you can log or return an error message
            return redirect()->back()->with('error', 'Error updating company: ' . $e->getMessage())->withErrors($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $company = CompanyModel::findOrFail($id);
            
            // If ProjectDetails exists, delete it
            if ($company) {
                $company->delete();
            }

            return redirect()->route('company.index')->with('success', 'Company Successfully Deleted');
        } catch (ModelNotFoundException $e) {
            return redirect()->route('company.index')->with('error', 'Project not found');
        } catch (\Exception $e) {
            // Handle other exceptions
            return redirect()->route('company.index')->with('error', 'An error occurred while deleting the project');
        }
    }
}
