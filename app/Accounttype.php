<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Accounttype extends Model
{
    use HasFactory;
    protected $table = 'account_type';
    protected $keyType = 'string'; // Menentukan tipe kunci sebagai string (UUID)
    public $incrementing = false; // Non-incrementing UUID
    protected $fillable = ['id','name','category'];

    public function coa()
    {
        return $this->hasMany(CoaM::class);
    }
}
