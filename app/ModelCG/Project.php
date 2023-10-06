<?php

namespace App\ModelCG;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    
    use HasFactory;
    protected $connection = 'mysql_secondary';
    protected $table = 'projects';
    protected $fillable = ['name']; 
}
