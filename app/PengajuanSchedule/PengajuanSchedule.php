<?php

namespace App\PengajuanSchedule;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\ModelCG\Project;

class PengajuanSchedule extends Model
{
    use HasFactory;
    protected $table = 'pengajuan_schedule';
    protected $fillable = ['employee','schedule_code','project','tanggal','shift','namapengaju','status','periode'];

    public function project()
    {
        return $this->belongsTo(Project::class, 'project');
    }
}
