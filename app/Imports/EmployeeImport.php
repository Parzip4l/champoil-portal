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
                'nama' => $row['NAMA LENGKAP'],
                'ktp' => $row['KTP'],
                'nik' => $row['NIK'],
                'referal_code' => $row['KODE REFERAL'],
                'divisi' => $row['DIVISI'],
                'jabatan' => $row['JABATAN'],
                'agama' => $row['AGAMA'],
                'jenis_kelamin' => $row['JENIS KELAMIN'],
                'email' => $row['EMAIL'],
                'telepon' => $row['NOMOR TELEPON'],
                'telepon_darurat' => $row['NOMOR TELEPON DARURAT'],
                'status_kontrak' => $row['STATUS KONTRAK'],
                'organisasi' => $row['ORGANISASI'],
                'joindate' => $row['TANGGAL MASUK'],
                'berakhirkontrak' => $row['TANGGAL BERAKHIR'],
                'tempat_lahir' => $row['TEMPAT LAHIR'],
                'tanggal_lahir' => $row['TANGGAL LAHIR'],
                'alamat' => $row['ALAMAT LENGKAP'],
                'alamat_ktp' => $row['ALAMAT LENGKAP DOMISILI'],
                'status_pernikahan' => $row['STATUS PERNIKAHAN'],
                'tanggungan' => $row['JUMLAH TANGGUNGAN'],
                'pendidikan_trakhir' => $row['PENDIDIKAN TERAKHIR'],
                'jurusan' => $row['JURUSAN'],
                'sertifikasi' => $row['SERTIFIKASI'],
                'expired_sertifikasi' => $row['EXPIRED DATE'],
            ]);            
        }
    }
}
