<?php

namespace App\ModelCG;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User_read_module extends Model
{
    use HasFactory;
    protected $connection = 'mysql_secondary';
    protected $table = 'user_read_modules';
    protected $fillable = ['id','eployee_code','id_module'];
}
