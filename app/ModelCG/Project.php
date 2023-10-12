<?php

namespace App\ModelCG;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;
    protected $connection = 'mysql_secondary';
    protected $table = 'projects';
    protected $fillable = ['id','name']; 

    public function schedules()
{
    return $this->hasMany(Schedule::class, 'project_id');
}
}
