<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payrol extends Model
{
    use HasFactory;
    protected $table = 'payrols';
    protected $fillable = ['id'];

    public function karyawan()
    {
        return $this->hasMany(Employee::class);
    }
}
