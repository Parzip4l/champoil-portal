<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Payrolinfo\Payrolinfo;
use App\Company\WorkLocation;
use App\Company\SceduleModel;
use App\User;

class Employee extends Model
{
    use HasFactory;
    protected $table = 'karyawan';
    protected $fillable = [
        'nama',
        'ktp',
        'nik',
        'referal_code',
        'alamat_ktp',
        'alamat',
        'work_location_id',
        'divisi',
        'pendidikan_trakhir',
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
        'level',
        'slack_id'
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

    public function workLocation()
    {
        return $this->belongsTo(WorkLocation::class);
    }

    public function schedules()
    {
        return $this->hasMany(SceduleModel::class);
    }
}
