<?php

namespace App\Company;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Company\LocationPositionSalary;

class WorkLocation extends Model
{
    use HasFactory;
    protected $table = 'company_work_location';
    protected $fillable = [
        'company_id',
        'name',
        'latitude',
        'longitude',
        'radius',
        'monthly_salary',
        'daily_rate',
    ];

    public function company()
    {
        return $this->belongsTo(\App\Company\CompanyModel::class);
    }

    public function employees()
    {
        return $this->hasMany(\App\Employee::class, 'work_location_id', 'id');
    }

    public function positionSalaries()
    {
        return $this->hasMany(LocationPositionSalary::class, 'work_location_id');
    }
}
