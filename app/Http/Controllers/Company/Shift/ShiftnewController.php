<?php

namespace App\Http\Controllers\Company\Shift;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Company\CompanySetting;
use App\Company\WorkLocation;
use App\Company\ShiftModel;
use Illuminate\Support\Facades\Auth;

class ShiftnewController extends Controller
{
    public function index($companyId)
    {

        $useShiftSetting = CompanySetting::where('company_id', $companyId)
                        ->where('key', 'use_shift')
                        ->first();

        $useShift = $useShiftSetting && $useShiftSetting->value === 1;
        if (!$useShift) {
            return redirect()->back()->with('error', 'Fitur shift belum diaktifkan, Silahkan aktifkan dan perbaharui fitur shift');
        }

        $shifts = ShiftModel::where('company_id', $companyId)->get();

        // ambil juga use_multilocation jika dibutuhkan di view
        $useMultilocation = CompanySetting::where('company_id', $companyId)
                            ->where('key', 'use_multilocation')
                            ->value('value') === 1;
        

        return view('pages.app-setting.company.shift.index', compact('companyId', 'shifts', 'useMultilocation'));
    }

    public function create($companyId)
    {
        $settings = CompanySetting::where('company_id', $companyId)->first();
        $locations = WorkLocation::where('company_id', $companyId)->get();
        $useMultilocation = CompanySetting::where('company_id', $companyId)
                          ->where('key', 'use_multilocation')
                          ->value('value') === 1;

        $workLocations = [];
        if ($useMultilocation) {
            $workLocations = WorkLocation::where('company_id', $companyId)->get();
        }

        return view('pages.app-setting.company.shift.create', compact('companyId', 'locations', 'settings','useMultilocation', 'workLocations'));
    }

    public function store(Request $request, $companyId)
    {
        $code = Auth::user()->employee_code;
        $request->validate([
            'name' => 'required',
            'code' => 'required',
            'start_time' => 'nullable|required_unless:is_off,1',
            'end_time' => 'nullable|required_unless:is_off,1',
            'location_id' => 'nullable|exists:company_work_location,id',
            'is_off' => 'nullable|boolean'
        ]);

        ShiftModel::create([
            'company_id' => $companyId,
            'name' => $request->name,
            'code' => $request->code,
            'start_time' => $request->is_off ? null : $request->start_time,
            'end_time' => $request->is_off ? null : $request->end_time,
            'is_off' => $request->is_off ? 1 : 0,
            'work_location_id' => $request->location_id,
            'created_by' => $code,
            'updated_by' => $code,
        ]);

        return redirect()->route('company.shifts.index', $companyId)->with('success', 'Shift berhasil ditambahkan.');
    }

    public function destroy($companyId, $id)
    {
        $shift = ShiftModel::where('company_id', $companyId)->findOrFail($id);
        $shift->delete();

        return redirect()->route('company.shifts.index', $companyId)->with('success', 'Shift berhasil dihapus.');
    }

    public function edit($companyId, ShiftModel $shift)
    {
        $useMultilocation = CompanySetting::where('company_id', $companyId)
            ->where('key', 'use_multilocation')
            ->value('value') === 1;

        $locations = [];

        if ($useMultilocation) {
            $locations = WorkLocation::where('company_id', $companyId)->get();
        }

        return view('pages.app-setting.company.shift.edit', compact('shift', 'companyId', 'useMultilocation', 'locations'));
    }

    public function update(Request $request, $companyId, ShiftModel $shift)
    {
        try {
            $code = Auth::user()->employee_code;

            $validated = $request->validate([
                'name' => 'required|string|max:100',
                'start_time' => 'nullable',
                'end_time' => 'nullable',
                'code' => 'required',
                'work_location_id' => 'nullable|exists:company_work_location,id',
            ]);
            $validated['updated_by'] = $code;
            $shift->update($validated);

            return redirect()->route('company.shifts.index', $companyId)
                ->with('success', 'Shift berhasil diperbarui.');
        } catch (\Throwable $e) {
            \Log::error('Shift update error', [
                'error' => $e->getMessage(),
                'stack' => $e->getTraceAsString(),
                'input' => $request->all()
            ]);
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat memperbarui shift: ' . $e->getMessage());
        }
    }

}
