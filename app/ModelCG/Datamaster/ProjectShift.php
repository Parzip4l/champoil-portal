<?php

namespace App\ModelCG\Datamaster;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectShift extends Model
{
    use HasFactory;
    protected $fillable = [
        'project_id',
        'shift_code',
        'jam_masuk',
        'jam_pulang'
    ];
}
