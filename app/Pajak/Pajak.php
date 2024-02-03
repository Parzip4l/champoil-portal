<?php

namespace App\Pajak;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pajak extends Model
{
    use HasFactory;
    protected $table = 'pajak';
    protected $fillable = [
        'status_pernikahan',
        'ter_code',
    ];

    public function details()
    {
        return $this->hasMany(PajakDetail::class, 'pajak_id');
    }
}
