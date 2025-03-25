<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PayrolComponent_NS extends Model
{
    use HasFactory;
    protected $table = 'payrol_component_ns';
    protected $fillable = ['allowences'];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_code', 'nik');
    }
}


