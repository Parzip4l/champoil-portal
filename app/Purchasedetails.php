<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchasedetails extends Model
{
    use HasFactory;
    protected $table = 'purchase_detail';
    protected $keyType = 'string'; // Menentukan tipe kunci sebagai string (UUID)
    public $incrementing = false; // Non-incrementing UUID
    protected $fillable = ['id','purchase_id','product_id','unit_price','quantity','untaxed','taxes','total_price'];

    public function purchase()
    {
        return $this->hasMany(Purchase::class);
    }
}
