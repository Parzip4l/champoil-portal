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
            'nama',
            'ktp',
            'nik',
            'referal_code',
            'divisi',
            'jabatan',
            'agama',
            'jenis_kelamin',
            'email',
            'telepon',
            'telepon_darurat',
            'status_kontrak',
            'organisasi',
            'joindate',
            'berakhirkontrak',
            'tempat_lahir',
            'tanggal_lahir',
            'alamat_ktp',
            'alamat',
            'status_pernikahan',
            'tanggungan',
            'pendidikan_trakhir',
            'jurusan',
            'sertifikasi',
            'expired_sertifikasi',
        ])
        ->get();
    }

    public function headings(): array
    {
        // Specify the headers for your exported data
        return [
            'NAMA LENGKAP',
            'KTP',
            'NIK',
            'KODE REFERAL',
            'DIVISI',
            'JABATAN',
            'AGAMA',
            'JENIS KELAMIN',
            'EMAIL',
            'NOMOR TELEPON',
            'NOMOR TELEPON DARURAT',
            'STATUS KONTRAK',
            'ORGANISASI',
            'TANGGAL MASUK',
            'TANGGAL BERAKHIR',
            'TEMPAT LAHIR',
            'TANGGAL LAHIR',
            'ALAMAT LENGKAP',
            'ALAMAT LENGKAP DOMISILI',
            'STATUS PERNIKAHAN',
            'JUMLAH TANGGUNGAN',
            'PENDIDIKAN TERAKHIR',
            'JURUSAN',
            'SERTIFIKASI',
            'EXPIRED DATE',
        ];
    }
}

