<?php

namespace App\Http\Controllers\Company\Shift;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Company\CompanySetting;
use App\Company\CompanyModel;
use App\Company\ScheduleModel;
use App\Company\ShiftModel;
use App\Employee;
use App\Company\WorkLocation;
use Illuminate\Support\Facades\Auth;

class SchedulenewController extends Controller
{
    public function index($companyId)
    {
        $settings = CompanySetting::where('company_id', $companyId)->pluck('value', 'key');

        if (empty($settings['use_schedule']) || $settings['use_schedule'] != 1) {
            return redirect()->back()->with('error', 'Fitur schedule belum diaktifkan, silahkan aktifkan fitur untuk bisa menggunakannya.');
        }

        $locations = WorkLocation::where('company_id', $companyId)->get();

        // Ambil bulan dan tahun dari parameter query string atau default ke sekarang
        $month = request()->get('month', now()->format('m'));
        $year = request()->get('year', now()->format('Y'));

        // Mengambil data schedule sesuai bulan dan tahun, lalu group by lokasi kerja
        $grouped = ScheduleModel::with(['employee', 'shift', 'workLocation'])
            ->where('company_id', $companyId)
            ->where('bulan', $month)
            ->where('tahun', $year)
            ->orderBy('work_date', 'asc')
            ->get()
            ->groupBy('work_location_id');

        // Shift Data
        $shifts = ShiftModel::where('company_id', $companyId)->get();

        return view('pages.app-setting.company.schedule.index', compact('grouped', 'companyId', 'settings', 'month', 'year','locations','shifts'));
    }


    public function create($companyId, Request $request)
    {

        $company = CompanyModel::findOrFail($companyId);

        $employees = Employee::where('unit_bisnis', $company->company_name)->get();
        $shifts = ShiftModel::where('company_id', $companyId)->get();
        $locations = WorkLocation::where('company_id', $companyId)->get();
        $settings = CompanySetting::where('company_id', $companyId)->pluck('value', 'key');

        $month = $request->month ?? now()->month;
        $year = $request->year ?? now()->year;

        $cutoffStart = null;
        $cutoffEnd = null;

        if (isset($settings['cutoff_start']) && isset($settings['cutoff_end'])) {
            $cutoffStartDay = (int) $settings['cutoff_start'];
            $cutoffEndDay = (int) $settings['cutoff_end'];

            $cutoffStart = \Carbon\Carbon::createFromDate($year, $month, $cutoffStartDay)->startOfDay();

            // cutoffEnd: bulan berikutnya
            $cutoffEnd = $cutoffStart->copy()->addMonth()->day($cutoffEndDay)->endOfDay();

            // Jika hari di bulan berikutnya tidak valid (misal cutoffEndDay = 31, tapi Februari), sesuaikan
            if ($cutoffEnd->day != $cutoffEndDay) {
                $cutoffEnd->day = $cutoffEnd->daysInMonth;
            }
        }

        return view('pages.app-setting.company.schedule.create', compact(
            'companyId', 'employees', 'shifts', 'locations', 'settings',
            'cutoffStart', 'cutoffEnd', 'month', 'year'
        ));
    }

    public function store(Request $request, $companyId)
    {
        $code = Auth::user()->employee_code;
        try {
            
            $request->validate([
                'employee_id' => 'required|exists:karyawan,id',
                'month' => 'required|integer|min:1|max:12',
                'year' => 'required|integer',
                'schedules' => 'required|array', // Pastikan data schedules ada
                'schedules.*.shift_id' => 'required|exists:company_shifts,id', // Setiap tanggal harus punya shift
                'schedules.*.shift_id' => 'required|exists:company_shifts,id', // Validasi shift_id untuk setiap tanggal
            ]);
    
            // Ambil nilai bulan dan tahun yang dipilih
            $month = $request->month;
            $year = $request->year;
            $periode = sprintf('%02d-%d', $month, $year);
            
            $exists = ScheduleModel::where('employee_id', $request->employee_id)
                ->where('bulan', $request->month)
                ->where('tahun', $request->year)
                ->exists();

            if ($exists) {
                return redirect()->back()->withInput()->with('error', 'Jadwal untuk karyawan dan periode ini sudah ada.');
            }
            // Loop untuk menyimpan schedule setiap tanggal
            foreach ($request->schedules as $date => $schedule) {
                // Pastikan tidak ada duplikat jadwal untuk karyawan pada bulan dan tahun yang sama
                $exists = ScheduleModel::where('employee_id', $request->employee_id)
                    ->where('work_date', $date)
                    ->exists();
    
                if ($exists) {
                    return redirect()->back()->withInput()->with('error', 'Jadwal untuk tanggal ' . \Carbon\Carbon::parse($date)->translatedFormat('d M Y') . ' sudah ada.');
                }
    
                // Simpan schedule
                ScheduleModel::create([
                    'company_id' => $companyId,
                    'employee_id' => $request->employee_id,
                    'shift_id' => $schedule['shift_id'],
                    'work_date' => $date,
                    'work_location_id' => $request->location_id, // Lokasi kerja, jika ada
                    'created_by' => $code,
                    'periode' => $periode,
                    'bulan' => $month,
                    'tahun' => $year,
                ]);
            }
    
            return redirect()->route('company.schedules.index', $companyId)->with('success', 'Jadwal berhasil ditambahkan.');
    
        } catch (\Exception $e) {
            // Jika terjadi kesalahan, tampilkan error ke log dan kembali dengan pesan error
            \Log::error('Error adding schedule: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan saat menambahkan jadwal. Silakan coba lagi.');
        }
    }

    public function edit($companyId, $id)
    {
        $schedule = ScheduleModel::with(['employee', 'shift', 'location'])
            ->where('company_id', $companyId)
            ->findOrFail($id);

        $employees = Employee::where('company_id', $companyId)->get();
        $shifts = ShiftModel::where('company_id', $companyId)->get();
        $locations = WorkLocation::where('company_id', $companyId)->get();
        $settings = CompanySetting::where('company_id', $companyId)->pluck('value', 'key');

        return view('company.schedule.edit', compact('companyId', 'schedule', 'employees', 'shifts', 'locations', 'settings'));
    }

    public function update(Request $request, $companyId, $id)
    {
        $request->validate([
            'employee_id' => 'required|exists:karyawan,id',
            'shift_id' => 'required|exists:shifts,id',
            'date' => 'required|date',
            'location_id' => 'nullable|exists:company_work_location,id',
        ]);

        try {
            $schedule = ScheduleModel::where('company_id', $companyId)->findOrFail($id);

            $schedule->update([
                'employee_id' => $request->employee_id,
                'shift_id' => $request->shift_id,
                'date' => $request->date,
                'location_id' => $request->location_id,
                'updated_by' => Auth::user()->employee_code,
            ]);

            return redirect()->route('company.schedules.index', $companyId)->with('success', 'Schedule berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }


    public function destroy($companyId, $id)
    {
        $schedule = ScheduleModel::where('company_id', $companyId)->findOrFail($id);
        $schedule->delete();

        return redirect()->route('company.schedules.index', $companyId)->with('success', 'Schedule berhasil dihapus.');
    }

    public function updateShift(Request $request, $companyId, $scheduleId)
    {
        // Validasi
        $request->validate([
            'shift_id' => 'required|exists:company_shifts,id', // Pastikan shift_id valid
        ]);

        // Cari jadwal yang akan diupdate
        $schedule = ScheduleModel::findOrFail($scheduleId);

        // Update shift
        $schedule->shift_id = $request->shift_id;
        $schedule->save();

        // Ambil data shift terbaru untuk ditampilkan
        $shift = $schedule->shift;

        // Kirim data kembali sebagai response
        return response()->json([
            'shiftCode' => $shift->code,
            'startTime' => $shift->start_time,
            'endTime' => $shift->end_time
        ]);
    }


}
