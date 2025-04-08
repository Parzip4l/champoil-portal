<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Company\WorkLocation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Company\CompanySetting;

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
        return view('pages.app-setting.company.multilokasi.create', compact('companyId'));
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

        WorkLocation::create([
            'company_id' => $companyId,
            'name' => $request->name,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'radius' => $request->radius,
            'monthly_salary' => $request->monthly_salary,
            'daily_rate' => $request->daily_rate,
        ]);

        return redirect()->route('company.work-locations.index', $companyId)
            ->with('success', 'Lokasi kerja berhasil ditambahkan.');
    }

    public function edit($companyId, $id)
    {
        $location = WorkLocation::where('company_id', $companyId)->findOrFail($id);
        return view('pages.app-setting.company.multilokasi.edit', compact('location', 'companyId'));
    }

    public function update(Request $request, $companyId, $id)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'radius' => 'required|numeric|min:1',
            'monthly_salary' => 'nullable|numeric|min:0',
            'daily_rate' => 'nullable|numeric|min:0',
        ]);

        $location = WorkLocation::where('company_id', $companyId)->findOrFail($id);

        $location->update([
            'name' => $request->name,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'radius' => $request->radius,
            'monthly_salary' => $request->monthly_salary,
            'daily_rate' => $request->daily_rate,
        ]);

        return redirect()->route('company.work-locations.index', $companyId)
            ->with('success', 'Lokasi kerja berhasil diperbarui.');
    }



    public function destroy($companyId, $id)
    {
        $lokasi = WorkLocation::where('company_id', $companyId)->findOrFail($id);
        $lokasi->delete();

        return redirect()->back()->with('success', 'Lokasi kerja berhasil dihapus.');
    }
}
