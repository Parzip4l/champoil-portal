<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CoaM extends Model
{
    use HasFactory;
    protected $table = 'coa';
    protected $keyType = 'string'; // Menentukan tipe kunci sebagai string (UUID)
    public $incrementing = false; // Non-incrementing UUID
    protected $fillable = ['id','code', 'name', 'type', 'reconciliation'];  

    public function accounttype()
    {
        return $this->hasMany(Accounttype::class);
    }
}
