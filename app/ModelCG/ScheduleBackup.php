<?php

namespace App\ModelCG;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScheduleBackup extends Model
{
    protected $table = 'schedule_backups';
    protected $fillable = ['project']; 

    public function project()
    {
        return $this->belongsTo(Project::class, 'id');
    }

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }
}
