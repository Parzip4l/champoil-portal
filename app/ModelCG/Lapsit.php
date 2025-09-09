<?php

namespace App\ModelCG;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lapsit extends Model
{
    use HasFactory;
    protected $fillable = [
        'project_id',
        'judul',
        'unix_code',
        'created_at',
        'category',
        // Add any other fields you want to be fillable
    ];
}
