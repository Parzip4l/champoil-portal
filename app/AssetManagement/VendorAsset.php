<?php

namespace App\AssetManagement;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;

class VendorAsset extends Model
{
    use HasFactory;
    use LogsActivity;
    protected $table = 'asset_vendor';
    protected $fillable = [
        'name'
    ];

    public function stocks()
    {
        return $this->hasMany(Stock::class, 'vendor_id');
    }

    public function transactions()
    {
        return $this->hasMany(Transaksi::class, 'vendor_id');
    }

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