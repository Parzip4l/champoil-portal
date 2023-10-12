<?php

namespace App\ModelCG;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Knowledge extends Model
{
    use HasFactory;
    protected $connection = 'mysql_secondary';
    protected $table = 'knowledge';
    protected $fillable = ['title','file_name']; 
}
