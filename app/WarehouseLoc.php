<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WarehouseLoc extends Model
{
    use HasFactory;
    protected $table = 'warehouse_loc';
    protected $keyType = 'string'; // Menentukan tipe kunci sebagai string (UUID)
    public $incrementing = false; // Non-incrementing UUID
    protected $fillable = ['id','code','name'];
}
