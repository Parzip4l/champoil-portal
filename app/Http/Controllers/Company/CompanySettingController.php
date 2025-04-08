<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Company\CompanyModel;
use App\Company\CompanySetting;
use App\Employee;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;

class CompanySettingController extends Controller
{
    public function edit($company_id)
    {
        $company = CompanyModel::findOrFail($company_id);
        $employeeTotal = Employee::where('unit_bisnis',$company->company_name)->count();
        $settings = $company->settings->pluck('value', 'key')->toArray();
        

        return view('pages.app-setting.company.company', compact('company', 'settings','employeeTotal'));
    }

    public function update(Request $request, $company_id)
    {
        $employee = Auth::user()->employee_code;
        $company = CompanyModel::findOrFail($company_id);
        // Set nilai default untuk checkbox yang tidak tercentang
        $checkboxKeys = [
            'use_shift',
            'late_cut_enabled',
            'use_pph21',
            'npwp_required',
            'use_radius',
            'allow_leave_conversion',
            'use_multilocation',
        ];
        foreach ($checkboxKeys as $key) {
            if (!$request->has($key)) {
                $request->merge([$key => 0]);
            }
        }

        // Jika menggunakan radius, gabungkan latitude dan longitude
        if ($request->input('use_radius')) {
            $request->merge([
                'gps_coordinates' => json_encode([
                    'latitude' => $request->input('latitude'),
                    'longitude' => $request->input('longitude'),
                ]),
                'radius_value' => $request->input('radius'), // ambil nilai radius
            ]);
        } else {
            $request->merge([
                'gps_coordinates' => null,
                'radius_value' => null,
            ]);
        }

        // Ambil semua data dari config
        $rules = config('company_settings.validation_rules');
        $data = $request->validate($rules);

        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $value = json_encode($value);
            }

            CompanySetting::updateOrCreate(
                ['company_id' => $company->id, 'key' => $key],
                [
                    'value' => $value,
                    'updated_by' => $employee,
                ]
            );
        }

        // Hapus data radius jika checkbox use_radius tidak aktif
        if (!$request->input('use_radius')) {
            CompanySetting::where('company_id', $company->id)
                ->whereIn('key', ['radius_value', 'gps_coordinates'])
                ->delete();
        }

        // Hapus nominal konversi cuti jika tidak diperbolehkan
        if (!$request->input('allow_leave_conversion')) {
            CompanySetting::where('company_id', $company->id)
                ->where('key', 'leave_conversion_amount')
                ->delete();
        }

        if ($request->input('redirect_to_location') === '1') {
            return redirect()->route('company.work-locations.index', $company_id)
                ->with('success', 'Pengaturan disimpan. Silakan atur lokasi kerja.');
        }


        return redirect()->back()->with('success', 'Company settings updated.');
    }

}
