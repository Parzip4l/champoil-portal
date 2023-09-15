<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Deliverysales extends Model
{
    use HasFactory;

    protected $table = 'delivery_sales';
    protected $keyType = 'string'; // Menentukan tipe kunci sebagai string (UUID)
    public $incrementing = false; // Non-incrementing UUID
    protected $fillable = ['id','code','so_id','so_code','customer_id','delivery_address','sales_team','expedition'];
}
