<?php

namespace App\Company;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyModel extends Model
{
    use HasFactory;
    protected $table = 'company';
    protected $fillable = [
        'company_code',
        'company_name',
        'company_address',
        'use_scedule',
        'schedule_type',
        'latitude',
        'longitude',
        'radius',
        'logo',
        'cutoff_start',
        'cutoff_end',
    ];
}
