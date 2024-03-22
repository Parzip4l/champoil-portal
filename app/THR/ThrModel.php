<?php

namespace App\THR;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ThrModel extends Model
{
    use HasFactory;
    protected $table = 'thr_master';
    protected $fillable = ['employee_code','gaji_pokok','allowances','deductions','thp','tahun','thr_status','slip_status','company','run_by'];
    
    // Tentukan bahwa primary key adalah UUID
    protected $primaryKey = 'id';

    // Tentukan bahwa primary key adalah UUID versi 4
    protected $keyType = 'string';

    // Nonaktifkan incrementing karena UUID tidak menggunakan auto-increment
    public $incrementing = false;

    // Atur secara otomatis nilai UUID saat membuat instance model baru
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id = (string) \Illuminate\Support\Str::uuid();
        });
    }

    public function karyawan()
    {
        return $this->hasMany(Employee::class);
    }
}
