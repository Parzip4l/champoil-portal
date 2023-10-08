<?php

namespace App\ModelCG;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectDetails extends Model
{
    use HasFactory;
    protected $connection = 'mysql_secondary';
    protected $table = 'project_details';
    protected $fillable = ['name']; 
}
