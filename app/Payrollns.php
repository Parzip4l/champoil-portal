<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Employee;
use App\Payrolinfo\Payrolinfo;

class Payrollns extends Model
{
    use HasFactory;
    protected $table = 'payrollns';
    protected $fillable = ['employee_code','month','year','periode','daily_salary','total_absen','lembur_salary','jam_lembur','total_lembur','uang_makan','uang_kerajinan','potongan_hutang','potongan_mess','potongan_lain','thp','total_daily','run_by','payrol_status','payslip_status','company'];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_code', 'nik');
    }

    public function payrolinfo()
    {
        return $this->hasOne(Payrolinfo::class, 'employee_code', 'employee_code');
    }
}
