<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PayrolComponent_NS extends Model
{
    use HasFactory;
    protected $table = 'payrol_component_ns';
    protected $fillable = ['allowences'];
}
