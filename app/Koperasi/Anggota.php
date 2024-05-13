<?php

namespace App\Koperasi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Anggota extends Model
{
    use HasFactory;

    protected $table = 'anggota_koperasi';
    protected $primaryKey = 'id'; 
    public $incrementing = false; 
    protected $keyType = 'string'; 
    protected $fillable = ['id', 'name', 'employee_code', 'company','join_date','member_status','loan_limit'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            // Generate UUID ketika membuat record baru
            $model->id = (string) Str::uuid();
        });
    }
}
