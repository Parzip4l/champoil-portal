<?php

namespace App\ModelCG;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Knowledge_soal extends Model
{
    use HasFactory;
    protected $connection = 'mysql_secondary';
    protected $table = 'knowledge_soals';
}
