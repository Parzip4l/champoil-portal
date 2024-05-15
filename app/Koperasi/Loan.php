<?php

namespace App\Koperasi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Loan extends Model
{
    protected $table = 'pengajuan_pinjaman';
    protected $primaryKey = 'id'; 
    public $incrementing = false; 
    protected $keyType = 'string'; 
    protected $fillable = ['id', 'company', 'nama', 'employee_code','amount','tenor','instalment','status','approve_by'];
}
