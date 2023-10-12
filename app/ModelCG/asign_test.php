<?php

namespace App\ModelCG;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class asign_test extends Model
{
    use HasFactory;
    protected $connection = 'mysql_secondary';
    protected $table = 'asign_tests';
}
