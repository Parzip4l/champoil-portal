<?php

namespace App\ModelCG;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jawaban_user extends Model
{
    use HasFactory;
    protected $connection = 'mysql_secondary';
    protected $table = 'jawaban_users';
}
