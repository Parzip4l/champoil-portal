<?php

namespace App\AssetManagement;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;

class TransaksiAsset extends Model
{
    use HasFactory;
    use LogsActivity;
    protected $table = 'asset_transaksi';
    protected $fillable = [
        'transaksi_code', 'nama', 'project', 'asset_id', 'vendor_id', 'qty'
    ];

    public function asset()
    {
        return $this->belongsTo(MasterAsset::class, 'asset_id');
    }

    public function vendor()
    {
        return $this->belongsTo(MasterVendor::class, 'vendor_id');
    }

    public function history()
    {
        return $this->hasMany(TransaksiHistory::class, 'transaksi_id');
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
