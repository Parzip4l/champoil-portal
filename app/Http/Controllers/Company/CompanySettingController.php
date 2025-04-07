<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Company\CompanyModel;
use App\Company\CompanySetting;
use App\Employee;

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
        $company = CompanyModel::findOrFail($company_id);

        // Set nilai default untuk checkbox yang tidak tercentang
        $checkboxKeys = [
            'use_shift',
            'late_cut_enabled',
            'use_pph21',
            'npwp_required',
            'use_radius',
            'allow_leave_conversion',
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

        // Ambil semua data yang akan disimpan
        $data = $request->only([
            'use_shift',
            'use_schedule',
            'late_cut_enabled',
            'late_minutes_threshold',
            'late_cut_amount',
            'payroll_type',
            'payroll_structure',
            'cutoff_start',
            'cutoff_end',
            'use_pph21',
            'pph21_method',
            'npwp_required',
            'use_radius',
            'radius_value',
            'gps_coordinates',
            'attendance_mode',
            'default_work_hours',
            'late_tolerance',
            'working_days',
            'annual_leave_quota',
            'max_leave_accumulation',
            'allow_leave_conversion',
            'leave_conversion_amount',
        ]);

        foreach ($data as $key => $value) {
            // Jika array (contoh: working_days atau gps_coordinates), encode ke JSON
            if (is_array($value)) {
                $value = json_encode($value);
            }

            CompanySetting::updateOrCreate(
                ['company_id' => $company->id, 'key' => $key],
                ['value' => $value]
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

        return redirect()->back()->with('success', 'Company settings updated.');
    }

}
