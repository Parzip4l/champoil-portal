<?php

namespace App\THR;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ThrComponent extends Model
{
    use HasFactory;
    protected $table = 'thr_component';

    // Hapus kolom 'id' dari fillable karena UUID akan menjadi primary key
    protected $fillable = [];

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
