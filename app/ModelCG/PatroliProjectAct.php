<?php

namespace App\ModelCG;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatroliProjectAct extends Model
{
    use HasFactory;
    protected $fillable = [
        'patroli_atc_id',
        'employee_id',
        'images',
        'remarks',
    ];
}
