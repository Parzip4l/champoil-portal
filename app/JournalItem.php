<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JournalItem extends Model
{
    use HasFactory;
    protected $table = 'journal_item';
    protected $keyType = 'string'; // Menentukan tipe kunci sebagai string (UUID)
    public $incrementing = false; // Non-incrementing UUID
    protected $fillable = ['id','journal', 'journal_entry','account'];
}
