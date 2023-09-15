<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorBill extends Model
{
    use HasFactory;
    protected $table = 'vendor_bills';
    protected $keyType = 'string'; // Menentukan tipe kunci sebagai string (UUID)
    public $incrementing = false; // Non-incrementing UUID
    protected $fillable = ['id','purchase_id','purchase_details','bill_date','accounting_date','bank_receipt','due_date','journal'];

    public function contact()
    {
        return $this->hasMany(ContactM::class);
    }
}
