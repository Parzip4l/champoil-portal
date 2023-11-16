<?php

namespace App\ModelCG;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectDetails extends Model
{
    use HasFactory;
    protected $connection = 'mysql_secondary';
    protected $table = 'project_details';
    protected $fillable = [
        'project_code',
        'jabatan',
        'kebutuhan',
        'p_gajipokok',
        'p_bpjstk',
        'p_bpjs_ks',
        'p_thr',
        'p_tkerja',
        'p_tseragam',
        'p_tlain',
        'p_training',
        'p_operasional',
        'p_membership',
        'r_deduction',
        'p_deduction',
        'tp_gapok',
        'tp_bpjstk',
        'tp_bpjsks',
        'tp_thr',
        'tp_tunjangankerja',
        'tp_tunjanganseragam',
        'tp_tunjanganlainnya',
        'tp_training',
        'tp_operasional',
        'tp_ppn',
        'tp_pph',
        'tp_cashin',
        'tp_total',
        'tp_membership',
        'tp_bulanan',
        'rate_harian',
    ];
}
