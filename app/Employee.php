<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;
    protected $table = 'karyawan';
    protected $fillable = ['id'];

    public function karyawan()
    {
        return $this->hasOne(UserController::class);
    }
}
