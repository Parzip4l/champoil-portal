<?php

namespace App\ModelCG;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatrliProject extends Model
{
    use HasFactory;
    protected $fillable = ['project_id', 'judul', 'unix_code'];
}
