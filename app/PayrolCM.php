<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PayrolCM extends Model
{
    use HasFactory;
    protected $table = 'payrol_components';
    protected $fillable = ['employee_code',
    'basic_salary',
    'allowances',
    'deductions',
    'thp'];

    public function karyawan()
    {
        return $this->hasMany(Employee::class);
    }
}
