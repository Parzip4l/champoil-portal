<?php

namespace App\PerformanceAppraisal;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;

class PaModel extends Model
{
    use HasFactory;
    use LogsActivity;
    protected $table = 'pa_table';

    // Hapus kolom 'id' dari fillable karena UUID akan menjadi primary key
    protected $fillable = [
        'nik',
        'nama',
        'periode',
        'detailsdata',
        'catatan_target',
        'nilai_keseluruhan',
        'komentar_masukan',
        'created_by',
        'approve_byemployee',
    ];


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
}
