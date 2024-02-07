<?php

namespace App\Component;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdditionalComponents extends Model
{
    use HasFactory;
    protected $table = 'additional_component';
    protected $fillable = ['employee_code','employee_name','components','company'];
}
