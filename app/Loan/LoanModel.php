<?php

namespace App\Loan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoanModel extends Model
{
    use HasFactory;
    protected $table = 'loans';
    protected $fillable = ['employee_id','amount','installments','remaining_amount','is_paid'];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
