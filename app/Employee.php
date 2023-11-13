<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Payrolinfo\Payrolinfo;

class Employee extends Model
{
    use HasFactory;
    protected $table = 'karyawan';
    protected $fillable = ['id'];

    public function karyawan()
    {
        return $this->hasOne(UserController::class);
    }

    public function payrolinfo()
    {
        return $this->hasOne(Payrolinfo::class, 'employee_code', 'nik');
    }
}
