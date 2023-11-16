<?php

namespace App\ModelCG;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;
    protected $table = 'schedules';
    protected $fillable = ['schedule_code','project', 'employee','tanggal','shift','periode']; 

    public function project()
    {
        return $this->belongsTo(Project::class, 'id');
    }

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }
}
