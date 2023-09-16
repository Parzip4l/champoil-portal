<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserActivities extends Model
{
    use HasFactory;
    protected $table = 'user_activities';
    protected $keyType = 'string'; // Menentukan tipe kunci sebagai string (UUID)
    public $incrementing = false; // Non-incrementing UUID
    protected $fillable = ['id','modul','username','action'];
}
