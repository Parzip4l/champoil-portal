<?php

namespace App\ModelCG;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payroll extends Model
{
    use HasFactory;
    protected $connection = 'mysql_secondary';
    protected $table = 'payrolls';
    protected $fillable = ['id','employee_code'];
}
