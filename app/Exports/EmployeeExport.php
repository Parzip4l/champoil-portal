<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Employee;

class EmployeeExport implements FromCollection, WithHeadings
{
    protected $unitBisnis;
    protected $loggedInUserNik;

    public function __construct($unitBisnis, $loggedInUserNik)
    {
        $this->unitBisnis = $unitBisnis;
        $this->loggedInUserNik = $loggedInUserNik;
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Employee::where('unit_bisnis', $this->unitBisnis)
        ->select([
            'ktp',
            'nik',
            'alamat',
            'jabatan',
            'organisasi',
            'status_kontrak',
            'joindate',
            'berakhirkontrak',
            'email',
            'telepon',
            'status_pernikahan',
            'agama',
            'tanggal_lahir',
            'tempat_lahir',
            'jenis_kelamin',
            'unit_bisnis',
            'tanggungan',
            'manager',
        ])
        ->get();
    }

    public function headings(): array
    {
        // Specify the headers for your exported data
        return [
            'ktp',
            'nik',
            'alamat',
            'jabatan',
            'organisasi',
            'status_kontrak',
            'joindate',
            'berakhirkontrak',
            'email',
            'telepon',
            'status_pernikahan',
            'agama',
            'tanggal_lahir',
            'tempat_lahir',
            'jenis_kelamin',
            'unit_bisnis',
            'tanggungan',
            'manager',
        ];
    }
}

