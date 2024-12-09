<?php

namespace App\Urbanica;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PayrolUrbanica extends Model
{
    use HasFactory;
    protected $table = 'payrol_urbanica';
    protected $fillable = [
        'employee_code',
        'periode',
        'year',
        'basic_salary',
        'allowances',
        'deductions',
        'thp',
        'payrol_status',
        'payslip_status',
        'run_by',
    ];

    protected $casts = [
        'allowances' => 'array',
        'deductions' => 'array',
    ];
}
