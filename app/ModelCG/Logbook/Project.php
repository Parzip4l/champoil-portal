<?php

namespace App\ModelCG\Logbook;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;
    protected $connection = 'mysql_logbook';
    protected $table = 'project';
}
