<?php

namespace App\Invoice;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;

class InvoiceModel extends Model
{
    use HasFactory;
    use LogsActivity;
    protected $table = 'invoice_ntr';
    
    protected $fillable = [
        'code', 'date', 'due_date', 'details', 'client', 'status', 'created_by', 'company',
    ];
}
