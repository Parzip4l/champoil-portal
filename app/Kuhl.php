<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kuhl extends Model
{
    use HasFactory;
    protected $table = 'kuhls';
    protected $fillable = ['id'];
}
