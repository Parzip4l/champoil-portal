<?php

namespace App\ModelCG;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScheduleBackup extends Model
{
    protected $table = 'schedule_backups';
    protected $fillable = ['project','employee','tanggal','shift','periode']; 

    public function project()
    {
        return $this->belongsTo(Project::class, 'project');
    }

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }

    public function projects()
    {
        return $this->belongsTo(Project::class, 'id');
    }
}
