<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payrollns extends Model
{
    use HasFactory;
    protected $table = 'payrollns';
    protected $fillable = ['employee_code'];
}
