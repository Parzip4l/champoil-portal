<?php

namespace App\KasManagement;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class CustomerManagement extends Model
{
    use HasFactory;
    protected $table = 'nt_customer';
    protected $primaryKey = 'id'; 
    public $incrementing = false; 
    protected $keyType = 'string'; 
    protected $fillable = ['name', 'company', 'job_status','handphone'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            // Generate UUID ketika membuat record baru
            $model->id = (string) Str::uuid();
        });
    }
}
