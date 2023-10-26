<?php

namespace App\GardaPratama;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gp extends Model
{
    use HasFactory;
    protected $table = 'gardapratama';
    protected $fillable = ['employee_id'];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
