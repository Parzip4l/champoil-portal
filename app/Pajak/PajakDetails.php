<?php

namespace App\Pajak;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PajakDetails extends Model
{
    use HasFactory;
    protected $table = 'pajak_details';
    protected $fillable = [
        'pajak_id',
        'min_bruto',
        'max_bruto',
        'persentase',
    ];

    public function pajak()
    {
        return $this->belongsTo(Pajak::class, 'pajak_id');
    }
}
