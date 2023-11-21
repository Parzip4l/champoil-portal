<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Employee;
use App\User;

class EmployeeImport implements ToCollection, WithHeadingRow
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            Employee::create([
                'ktp' => $row['ktp'],
                'nik' => $row['nik'],
                'nama' => $row['nama'],
                'alamat' => $row['alamat'],
                'jabatan' => $row['jabatan'],
                'organisasi' => $row['organisasi'],
                'status_kontrak' => $row['status_kontrak'],
                'joindate' => $row['joindate'],
                'berakhirkontrak' => $row['berakhirkontrak'],
                'email' => $row['email'],
                'telepon' => $row['telepon'],
                'status_pernikahan' => $row['status_pernikahan'],
                'agama' => $row['agama'],
                'tanggal_lahir' => $row['tanggal_lahir'],
                'tempat_lahir' => $row['tempat_lahir'],
                'jenis_kelamin' => $row['jenis_kelamin'],
                'unit_bisnis' => $row['unit_bisnis'],
                'tanggungan' => $row['tanggungan'],
                'manager' => $row['manager'],
            ]);
        }
    }
}
