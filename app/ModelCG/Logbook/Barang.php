<?php

namespace App\ModelCG\Logbook;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    use HasFactory;
    protected $connection = 'mysql_logbook';
    protected $table = 'activity_barangs';
}
