<?php

namespace App\Company;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Company\WorkLocation;

class LocationPositionSalary extends Model
{
    use HasFactory;
    protected $table = 'company_work_location_salary';
    protected $fillable = [
        'company_id',
        'work_location_id',
        'position_id',
        'monthly_salary',
        'daily_rate',
    ];

    public function location()
    {
        return $this->belongsTo(WorkLocation::class, 'work_location_id');
    }

    public function position()
    {
        return $this->belongsTo(\App\ModelCG\Jabatan::class, 'position_id', 'id')
                    ->setConnection('mysql_secondary');
    }
}
