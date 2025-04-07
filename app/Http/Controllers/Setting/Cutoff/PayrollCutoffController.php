<?php

namespace App\Http\Controllers\Setting\Cutoff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

// Model
use App\Setting\Cutoff\PayrollCutoffSetting;
use App\Setting\Cutoff\PayrollCutoffDetail;
use App\Company\CompanyModel;
use Illuminate\Support\Facades\Auth;
use App\ModelCG\Jabatan;
use App\Organisasi\Organisasi;


class PayrollCutoffController extends Controller
{
    public function edit()
    {
        $company = Auth::user()->company;

        // Cek apakah company ditemukan
        $companyData = CompanyModel::where('company_name', $company)->first();
        if (!$companyData) {
            return redirect()->back()->with('error', 'Company tidak ditemukan.');
        }

        $companyId = $companyData->id;

        // Ambil departemen & posisi sesuai company
        $departments = Organisasi::where('company', $company)->get();
        $positions = Jabatan::where('parent_category', $company)->get();

        // Ambil setting payroll cutoff
        $setting = PayrollCutoffSetting::with('details')->where('company_id', $companyId)->first();

        // Jika belum ada setting, buat object kosong agar tidak null
        if (!$setting) {
            $setting = new PayrollCutoffSetting([
                'is_uniform' => true,
                'details' => collect() // Hindari null, pastikan array kosong
            ]);
        }

        return view('pages.app-setting.cutoff.cutoff', compact('setting', 'departments', 'positions'));
    }


    public function update(Request $request)
    {
        try {
            $company = Auth::user()->company;
            $companyData = CompanyModel::where('company_name', $company)->first();
            $companyId = $companyData->id;

            // Validasi input
            $data = $request->validate([
                'is_uniform' => 'required|boolean',
                'start_date' => 'nullable|string',
                'end_date' => 'nullable|string',
                'process_date' => 'nullable|string',
                'details' => 'nullable|array',
                'details.*.type' => 'required|string|in:department,organization',
                'details.*.ref_id' => 'required|numeric|exists:organisasi,id',
                'details.*.start_date' => 'required|string',
                'details.*.end_date' => 'required|string',
                'details.*.process_date' => 'required|string',
            ]);

            // Simpan pengaturan cutoff utama
            $setting = PayrollCutoffSetting::updateOrCreate(
                ['company_id' => $companyId],
                [
                    'is_uniform' => $data['is_uniform'],
                    'start_date' => $data['start_date'] ?? null,
                    'end_date' => $data['end_date'] ?? null,
                    'process_date' => $data['process_date'] ?? null,
                ]
            );

            // Hapus detail cutoff lama
            $setting->details()->delete();

            // Simpan detail cutoff baru jika tidak uniform
            if (!$data['is_uniform'] && isset($data['details'])) {
                foreach ($data['details'] as $detail) {
                    $setting->details()->create([
                        'company_id' => $companyId,
                        'type' => $detail['type'],
                        'ref_id' => $detail['ref_id'],
                        'start_date' => $detail['start_date'],
                        'end_date' => $detail['end_date'],
                        'process_date' => $detail['process_date'],
                    ]);
                }
            }

            return redirect()->back()->with('success', 'Pengaturan cutoff berhasil disimpan.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }



}
