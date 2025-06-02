<?php

namespace App\Payrolinfo;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Payrollns;

class Payrolinfo extends Model
{
    use HasFactory;
    protected $table = 'payrolinfos';
    protected $fillable = ['employee_code'];

    public function karyawan()
    {
        return $this->hasMany(Employee::class);
    }

    public function payroll()
    {
        return $this->belongsTo(Payrollns::class, 'employee_code', 'employee_code');
    }
}
