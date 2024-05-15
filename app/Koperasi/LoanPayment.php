<?php

namespace App\Koperasi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class LoanPayment extends Model
{
    protected $table = 'loan_payments';
    protected $primaryKey = 'id'; 
    public $incrementing = false; 
    protected $keyType = 'string'; 
    protected $fillable = ['id', 'loan_id', 'tanggal_pembayaran', 'jumlah_pembayaran','sisahutang'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            // Generate UUID ketika membuat record baru
            $model->id = (string) Str::uuid();
        });
    }
}
