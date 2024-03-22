<?php

namespace App\THR;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ThrComponentModel extends Model
{
    use HasFactory;
    protected $table = 'thr_component_models';
    protected $fillable = ['id'];
}
