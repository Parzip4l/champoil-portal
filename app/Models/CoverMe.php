<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CoverMe extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_perusahaan',
        'nik_cover',
        'tanggal',
        'shift',
        'requirements',
    ];
}
