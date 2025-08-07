<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CoverClaim extends Model
{
    use HasFactory;

    protected $fillable = [
        'cover_id',
        'nik',
        'status'
    ];
}
