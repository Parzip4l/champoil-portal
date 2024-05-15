<?php

namespace App\Koperasi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Saving extends Model
{
    use HasFactory;
    protected $table = 'savings';
    protected $primaryKey = 'id'; 
    public $incrementing = false; 
    protected $keyType = 'string'; 
    protected $fillable = ['id', 'employee_id', 'tanggal_simpan', 'jumlah_simpan','keterangan'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            // Generate UUID ketika membuat record baru
            $model->id = (string) Str::uuid();
        });
    }
}
