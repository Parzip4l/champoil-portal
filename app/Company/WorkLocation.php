<?php

namespace App\Company;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        return $this->belongsTo(CompanyModel::class);
    }
}
