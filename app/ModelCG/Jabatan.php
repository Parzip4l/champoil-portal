<?php

namespace App\ModelCG;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jabatan extends Model
{
    use HasFactory;
    protected $connection = 'mysql_secondary';
    protected $table = 'jabatans';
    protected $fillable = ['name', 'parent_category']; 

    protected $primaryKey = 'id'; // Use 'id' as the primary key name
    public $incrementing = false; // Disable auto-incrementing for UUIDs
    protected $keyType = 'string';
}
