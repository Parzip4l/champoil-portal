<?php

namespace App\ModelCG;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;
    protected $connection = 'mysql_secondary';
    protected $table = 'projects';
    protected $fillable = ['id','name','badan', 'latitude', 'longtitude', 'contract_start', 'end_contract','company']; 

    public function schedules()
    {
        return $this->hasMany(Schedule::class, 'project_id');
    }

    public function schedulebackup()
    {
        return $this->hasMany(ScheduleBackup::class, 'project_id');
    }

    public function schedulesPengajuan()
    {
        return $this->hasMany(PengajuanSchedule::class, 'project_id');
    }
}
