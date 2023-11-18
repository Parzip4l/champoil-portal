<?php

namespace App\Exports;

use App\Absen;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AttendenceExport implements FromCollection, WithHeadings
{

    protected $unitBisnis;

    public function __construct($unitBisnis)
    {
        $this->unitBisnis = $unitBisnis;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Absen::where('unit_bisnis', $this->unitBisnis)
            ->join('karyawan', 'absens.nik', '=', 'karyawan.nik')
            ->select('karyawan.nama', 'absens.tanggal', 'absens.clock_in', 'absens.clock_out', 'absens.status')
            ->get();
    }


    /**
     * @return array
     */
    public function headings(): array
    {
        // Specify the headers for your exported data
        return [
            'Nama Karyawan',
            'Tanggal',
            'Clock In',
            'Clock Out',
            'Status',
        ];
    }
}
