<?php

namespace App\Exports;

use App\ModelCG\Schedule;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ScheduleExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Schedule::where('periode','November-2023')
        ->select('schedule_code', 'project', 'employee', 'tanggal', 'shift', 'periode')
        ->get();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        // Specify the headers for your exported data
        return [
            'schedule_code',
            'project',
            'employee',
            'tanggal',
            'shift',
            'periode',
        ];
    }
}
