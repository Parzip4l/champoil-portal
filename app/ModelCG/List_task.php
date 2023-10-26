<?php

namespace App\ModelCG;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class List_task extends Model
{
    use HasFactory;
    protected $connection = 'mysql_secondary';
    protected $table = 'list_task';
}
