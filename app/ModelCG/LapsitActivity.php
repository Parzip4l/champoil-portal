<?php

namespace App\ModelCG;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LapsitActivity extends Model
{
    use HasFactory;
    protected $fillable = [
        'lapsit_id',
        'employee',
        'images',
        'remarks',
    ];
}
