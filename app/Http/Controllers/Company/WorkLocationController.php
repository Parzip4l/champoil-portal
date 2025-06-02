<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Company\WorkLocation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Company\CompanySetting;
use App\Company\CompanyModel;
use App\Company\LocationPositionSalary;
use Illuminate\Support\Arr;

// Model
use App\ModelCG\Jabatan;

class WorkLocationController extends Controller
{
    public function index($companyId)
    {
        $locations = WorkLocation::where('company_id', $companyId)->get();

        $useMultilocation = CompanySetting::where('company_id', $companyId)
        ->where('key', 'use_multilocation')
        ->value('value') == '1';
        
        foreach ($locations as $loc) {
            $lat = $loc->latitude;
            $lon = $loc->longitude;
    
            // Panggil Nominatim (OpenStreetMap)
            $response = Http::withHeaders([
                'User-Agent' => 'HRIS-TRUEST/1.0 (admin@yourcompany.com)'
            ])->get('https://nominatim.openstreetmap.org/reverse', [
                'lat' => $loc->latitude,
                'lon' => $loc->longitude,
                'format' => 'json'
            ]);
        
            if ($response->ok() && isset($response['display_name'])) {
                $loc->resolved_address = $response['display_name'];
            } else {
                $loc->resolved_address = '-';
            }
        }
        return view('pages.app-setting.company.multilokasi.index', compact('locations', 'companyId','useMultilocation'));
    }

    public function create($companyId)
    {
        $company = CompanyModel::where('id', $companyId)->value('company_name');
        $positions = Jabatan::on('mysql_secondary')->where('parent_category',$company)->get();
        return view('pages.app-setting.company.multilokasi.create', compact('companyId','positions'));
    }

    public function store(Request $request, $companyId)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'radius' => 'required|numeric|min:1',
            'monthly_salary' => 'nullable|numeric|min:0',
            'daily_rate' => 'nullable|numeric|min:0',
        ]);

        $location = WorkLocation::create([
            'company_id' => $companyId,
            'name' => $request->name,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'radius' => $request->radius,
        ]);
        if ($request->has('position_salaries')) {
            foreach ($request->position_salaries as $positionSalary) {
                LocationPositionSalary::create([
                    'company_id' => $companyId,
                    'work_location_id' => $location->id, 
                    'position_id' => $positionSalary['position_id'],
                    'monthly_salary' => isset($positionSalary['monthly_salary']) ? (int) str_replace('.', '', $positionSalary['monthly_salary']) : null,
                    'daily_rate' => isset($positionSalary['daily_rate']) ? (int) str_replace('.', '', $positionSalary['daily_rate']) : null,
                ]);
            }
        }

        return redirect()->route('company.work-locations.index', $companyId)
            ->with('success', 'Lokasi kerja berhasil ditambahkan.');
    }

    public function edit($companyId, $locationId)
    {
        $companyName = CompanyModel::where('id', $companyId)->value('company_name');

        // Ambil lokasi kerja
        $location = WorkLocation::where('company_id', $companyId)->findOrFail($locationId);

        // Ambil data salary yang sudah ada, dengan relasi jabatan menggunakan manual query
        $positionSalaries = LocationPositionSalary::where('work_location_id', $locationId)->get();

        // Ambil semua posisi untuk company ini
        $allPositions = Jabatan::on('mysql_secondary')
            ->where('parent_category', $companyName)
            ->get();

        // Filter posisi yang belum digunakan
        $usedPositionIds = $positionSalaries->pluck('position_id')->toArray();

        $availablePositions = $allPositions->whereNotIn('id', $usedPositionIds)->values();

        // Gabungkan posisi dengan salary data (cross database)
        foreach ($positionSalaries as $positionSalary) {
            $positionSalary->position = Jabatan::on('mysql_secondary')->find($positionSalary->position_id);
        }

        return view('pages.app-setting.company.multilokasi.edit', compact(
            'companyId',
            'location',
            'positionSalaries',
            'availablePositions'
        ));
    }

    public function show($companyId, $locationId)
    {
        $companyName = CompanyModel::where('id', $companyId)->value('company_name');

        // Ambil lokasi kerja
        $location = WorkLocation::where('company_id', $companyId)->findOrFail($locationId);

        // Ambil data salary yang sudah ada, dengan relasi jabatan menggunakan manual query
        $positionSalaries = LocationPositionSalary::where('work_location_id', $locationId)->get();

        // Ambil semua posisi untuk company ini
        $allPositions = Jabatan::on('mysql_secondary')
            ->where('parent_category', $companyName)
            ->get();

        // Filter posisi yang belum digunakan
        $usedPositionIds = $positionSalaries->pluck('position_id')->toArray();

        $availablePositions = $allPositions->whereNotIn('id', $usedPositionIds)->values();

        // Gabungkan posisi dengan salary data (cross database)
        foreach ($positionSalaries as $positionSalary) {
            $positionSalary->position = Jabatan::on('mysql_secondary')->find($positionSalary->position_id);
        }

        return view('pages.app-setting.company.multilokasi.show', compact(
            'companyId',
            'location',
            'positionSalaries',
            'availablePositions'
        ));
    }

    public function update(Request $request, $companyId, $locationId)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'radius' => 'required|numeric|min:1',
            'position_salaries.*.position_id' => 'required|uuid',
            'position_salaries.*.monthly_salary' => 'nullable|numeric|min:0',
            'position_salaries.*.daily_rate' => 'nullable|numeric|min:0',
        ]);

        $location = WorkLocation::where('company_id', $companyId)->findOrFail($locationId);
        $location->update([
            'name' => $request->name,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'radius' => $request->radius,
        ]);

        if ($request->has('position_salaries')) {
            $existingSalaries = LocationPositionSalary::where('company_id', $companyId)
                ->where('work_location_id', $locationId)
                ->get()
                ->keyBy('position_id');
        
            $incomingIds = [];
        
            foreach ($request->position_salaries as $positionSalary) {
                $positionId = $positionSalary['position_id'];
                $monthlySalary = isset($positionSalary['monthly_salary']) ? (int) str_replace('.', '', $positionSalary['monthly_salary']) : null;
                $dailyRate = isset($positionSalary['daily_rate']) ? (int) str_replace('.', '', $positionSalary['daily_rate']) : null;
        
                $incomingIds[] = $positionId;
        
                if ($existingSalaries->has($positionId)) {
                    // Update jika sudah ada
                    $existingSalaries[$positionId]->update([
                        'monthly_salary' => $monthlySalary,
                        'daily_rate' => $dailyRate,
                    ]);
                } else {
                    // Insert baru jika belum ada
                    LocationPositionSalary::create([
                        'company_id' => $companyId,
                        'work_location_id' => $locationId,
                        'position_id' => $positionId,
                        'monthly_salary' => $monthlySalary,
                        'daily_rate' => $dailyRate,
                    ]);
                }
            }
        
            // Hapus yang tidak termasuk dalam request (jika ingin sinkron penuh)
            LocationPositionSalary::where('company_id', $companyId)
                ->where('work_location_id', $locationId)
                ->whereNotIn('position_id', $incomingIds)
                ->delete();
        }

        return redirect()->route('company.work-locations.index', $companyId)
            ->with('success', 'Data lokasi kerja berhasil diperbarui.');
    }


    public function destroy($companyId, $id)
    {
        $lokasi = WorkLocation::where('company_id', $companyId)->findOrFail($id);
        $lokasi->delete();

        return redirect()->back()->with('success', 'Lokasi kerja berhasil dihapus.');
    }
}
