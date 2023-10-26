<?php

namespace App\Loan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoanModel extends Model
{
    use HasFactory;
    protected $table = 'loans';
    protected $fillable = ['employee_id'];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
