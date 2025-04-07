<?php

namespace App\Setting\Cutoff;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PayrollCutoffDetail extends Model
{
    use HasFactory;
    protected $table = 'payroll_cutoff_details';
    protected $fillable = [
        'payroll_cutoff_setting_id',
        'company_id',
        'type',
        'ref_id',
        'start_date',
        'end_date',
        'process_date',
    ];

    public function setting()
    {
        return $this->belongsTo(PayrollCutoffSetting::class);
    }

    public function company()
    {
        return $this->belongsTo(CompanyModel::class);
    }
}
