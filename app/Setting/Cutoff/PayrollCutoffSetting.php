<?php

namespace App\Setting\Cutoff;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PayrollCutoffSetting extends Model
{
    use HasFactory;
    protected $table = 'payroll_cutoff_setting';
    protected $fillable = ['company_id', 'is_uniform', 'start_date', 'end_date', 'process_date'];

    public function company()
    {
        return $this->belongsTo(CompanyModel::class);
    }

    public function details()
    {
        return $this->hasMany(PayrollCutoffDetail::class);
    }
}
