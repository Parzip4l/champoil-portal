<?php

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

use App\Employee;
use App\User;
use App\Setting\Golongan\GolonganModel;

use App\Version;

class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('pages.app-setting.index');
    }

    public function apps_version()
    {
        $data['records']=Version::all();
        return view('pages.app-setting.apps_versions',$data);
    }

    public function save_version(Request $request)
    {
        $data = $request->all();
        $insert = [
            'android'=>$data['android'],
            'ios'=>$data['ios'],
            'created_at'=>date('Y-m-d')
        ];

        Version::insert($insert);

        return redirect()->route('version')->with('success', 'Data Successfully Added');
    }

    // Company Setting

    public function IndexGolongan()
    {
        $code = Auth::user()->employee_code;
        $company = Employee::where('nik', $code)->first();

        $golongan = GolonganModel::where('company',$company->unit_bisnis)->get();
        return view('pages.app-setting.golongan.index', compact('golongan'));
    }

    public function StoreGolongan(Request $request)
    {
        $code = Auth::user()->employee_code;
        $company = Employee::where('nik', $code)->first();

        try {
            // Simpan pengumuman
            $golongan = new GolonganModel;
            $golongan->name = $request->name;
            $golongan->company = $company->unit_bisnis;
            $golongan->save();

            return redirect()->route('golongan.index')->with('success', 'Data Golongan Berhasil Dibuat');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function UpdateGolongan(Request $request, $id)
    {
        $code = Auth::user()->employee_code;
        $company = Employee::where('nik', $code)->first();

        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        try {
            $golongan = GolonganModel::findOrFail($id);
            $golongan->name = $request->name;

            $golongan->save();

            return redirect()->route('golongan.index')->with('success', 'Data Golongan berhasil diperbarui');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function DeleteGolongan($id)
    {
        try {
            $golongan = GolonganModel::findOrFail($id);

            $golongan->delete();

            return redirect()->route('golongan.index')->with('success', 'Data Golongan berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
