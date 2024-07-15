<?php

namespace App\AssetManagement;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;

class TransaksiHistory extends Model
{
    use HasFactory;
    use LogsActivity;
    protected $table = 'asset_transaksihistory';
    protected $fillable = [
        'transaksi_id', 'status', 'comment'
    ];

    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class, 'transaksi_id');
    }
}
