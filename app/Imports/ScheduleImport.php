<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\ModelCG\Schedule;
use Carbon\Carbon;


class ScheduleImport implements ToCollection, WithHeadingRow
{
    /**
     * @param Collection $collection
     */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            // Periksa apakah jadwal dengan kode karyawan dan tanggal yang sama sudah ada
            $existingSchedule = Schedule::where('employee', $row['employee'])
                ->whereDate('tanggal', Carbon::parse($row['tanggal'])->toDateString())
                ->first();
                
            if ($existingSchedule) {
                // Jika sudah ada, perbarui catatan yang sudah ada
                $existingSchedule->update([
                    'schedule_code' => $row['schedule_code'],
                    'project' => $row['project'],
                    'shift' => $row['shift'],
                    'periode' => $row['periode'],
                ]);
            } else {
                // Jika belum ada, buat catatan baru
                Schedule::create([
                    'schedule_code' => $row['schedule_code'],
                    'project' => $row['project'],
                    'employee' => $row['employee'],
                    'tanggal' => Carbon::parse($row['tanggal'])->toDateString(),
                    'shift' => $row['shift'],
                    'periode' => $row['periode'],
                ]);
            }
        }
    }
}
