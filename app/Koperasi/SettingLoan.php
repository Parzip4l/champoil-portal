<?php

namespace App\Koperasi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class SettingLoan extends Model
{
    use HasFactory;
    protected $table = 'loan_setting';
    protected $primaryKey = 'id'; 
    public $incrementing = false; 
    protected $keyType = 'string'; 
    protected $fillable = ['id', 'company', 'min_saving', 'max_saving','max_limit'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            // Generate UUID ketika membuat record baru
            $model->id = (string) Str::uuid();
        });
    }
}
