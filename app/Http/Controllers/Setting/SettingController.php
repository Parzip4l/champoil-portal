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
use App\ModelCG\BirthdaysMessages;
use Carbon\Carbon;


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

    public function birthdays_messages(){
        // Ambil data karyawan dengan unit_bisnis 'Kas'
        $data['birth_days'] = Employee::where('unit_bisnis', 'Kas')->get()->map(function ($employee) {
            // Konversi tanggal_lahir ke objek Carbon
            $tanggalLahir = Carbon::parse($employee->tanggal_lahir);

            // Buat tanggal ulang tahun untuk tahun ini
            $ulangTahun = Carbon::createFromDate(now()->year, $tanggalLahir->month, $tanggalLahir->day);

            // Tambahkan atribut ulang_tahun ke dalam data karyawan
            $employee->ulang_tahun = $ulangTahun->toDateString();

            return $employee;
        });

        // Formatkan data ulang tahun ke array JSON-like
        $birthdays = $data['birth_days']->map(function ($employee) {
            return [
                'id' => $employee->id,
                'start' => $employee->ulang_tahun,
                'end' => $employee->ulang_tahun,
                'title' => "{$employee->nama} Birthday"
            ];
        });

        // Pass data to view
        $data['birthdays'] = $birthdays->toArray(); // Convert to array for view compatibility

        return view('pages.app-setting.birthdays_message', $data);
    }


    public function save_messages(Request $request)
    {
        $data = $request->all();
        $insert = [
            'tanggal'=>$data['tanggal_tahun'],
            'message'=>$data['message'],
            'created_at'=>date('Y-m-d')
        ];

        BirthdaysMessages::insert($insert);

        return redirect()->route('birthdays-messages')->with('success', 'Data Successfully Added');
    }
}
