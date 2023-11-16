<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\ModelCG\Schedule;

class ScheduleImport implements ToCollection, WithHeadingRow
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            Schedule::create([
                'schedule_code' => $row['schedule_code'],
                'project' => $row['project'],
                'employee' => $row['employee'],
                'tanggal' => $row['tanggal'],
                'shift' => $row['shift'],
                'periode' => $row['periode'],
            ]);
        }
    }
}
