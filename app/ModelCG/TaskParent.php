<?php

namespace App\ModelCG;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskParent extends Model
{
    use HasFactory;
    protected $connection = 'mysql_secondary';
    protected $table = 'task_parent';
}
