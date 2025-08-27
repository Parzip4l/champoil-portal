<?php

namespace App\Models\log;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogAbsen extends Model
{
    use HasFactory;

    protected $fillable = [
        'nik',
        'longitude',
        'latitude',
        'description',
    ];
}
