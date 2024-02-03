<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Employee;
use App\User;

class EmployeeImport implements ToCollection, WithHeadingRow
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $rows)
    {
        $code = Auth::user()->employee_code;
        $company = Employee::where('nik', $code)->first();
        foreach ($rows as $row) {
            $unit_bisnis = $company->unit_bisnis;
            $useraccess = ["dashboard_access"];
            $hashedPassword = Hash::make($row['password']);

            Employee::create([
                'nama' => $row['nama'],
                'ktp' => $row['ktp'],
                'nik' => $row['nik'],
                'referal_code' => $row['referal_code'],
                'divisi' => $row['divisi'],
                'jabatan' => $row['jabatan'],
                'agama' => $row['agama'],
                'jenis_kelamin' => $row['jenis_kelamin'],
                'email' => $row['email'],
                'telepon' => $row['telepon'],
                'telepon_darurat' => $row['telepon_darurat'],
                'status_kontrak' => $row['status_kontrak'],
                'organisasi' => $row['organisasi'],
                'joindate' => $row['joindate'],
                'berakhirkontrak' => $row['berakhirkontrak'],
                'tempat_lahir' => $row['tempat_lahir'],
                'tanggal_lahir' => $row['tanggal_lahir'],
                'alamat' => $row['alamat'],
                'alamat_ktp' => $row['alamat_ktp'],
                'status_pernikahan' => $row['status_pernikahan'],
                'tanggungan' => $row['tanggungan'],
                'pendidikan_trakhir' => $row['pendidikan_trakhir'],
                'jurusan' => $row['jurusan'],
                'unit_bisnis' => $unit_bisnis,
                'sertifikasi' => $row['sertifikasi'],
                'expired_sertifikasi' => $row['expired_sertifikasi'],
            ]);

            User::create([
                'name' => $row['nik'],
                'email' => $row['email'],
                'password' => $hashedPassword,
                'permission' => json_encode($useraccess),
                'employee_code' => $row['nik'],
                'company' => $unit_bisnis,
            ]);        
        }
    }
}
