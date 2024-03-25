<?php

namespace App\Component;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComponentDetails extends Model
{
    use HasFactory;
    protected $table = 'additional_component_details';
    protected $fillable = ['employee_code','employee_name','code_master','component_code','component_name','nominal','type'];
}
