<?php

namespace App\ModelCG;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenempatanHistory extends Model
{
    use HasFactory;
    protected $connection = 'mysql_cg';
    protected $table = 'penempatan_histories';
}
