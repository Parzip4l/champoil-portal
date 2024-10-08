<?php

namespace App\ModelCG;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;
    protected $table = 'schedules';
    protected $fillable = ['schedule_code','project', 'employee','tanggal','shift','periode']; 
    protected $dateFormat = 'Y-m-d';

    public function project()
    {
        return $this->belongsTo(Project::class, 'project');
    }

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }

    public function project2()
    {
        return $this->belongsTo(Project::class, 'id');
    }
}
