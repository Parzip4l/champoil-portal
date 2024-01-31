<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Payrolinfo\Payrolinfo;
use App\User;

class Employee extends Model
{
    use HasFactory;
    protected $table = 'karyawan';
    protected $fillable = [
        'ktp',
        'nik',
        'nama',
        'alamat_ktp',
        'alamat',
        'divisi',
        'pendidikan_terakhir',
        'jurusan',
        'sertifikasi',
        'expired_sertifikasi',
        'telepon_darurat',
        'jabatan',
        'organisasi',
        'status_kontrak',
        'joindate',
        'berakhirkontrak',
        'email',
        'telepon',
        'organisasi',
        'status_pernikahan',
        'tanggungan',
        'agama',
        'tanggal_lahir',
        'tempat_lahir',
        'jenis_kelamin',
        'unit_bisnis',
        'manager',
    ];

    public function karyawan()
    {
        return $this->hasOne(UserController::class);
    }

    public function payrolinfo()
    {
        return $this->hasOne(Payrolinfo::class, 'employee_code', 'nik');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'employee_code', 'nik');
    }
}
