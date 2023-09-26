<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penetrasi extends Model
{
    use HasFactory;
    protected $table = 'penetrasis';
    protected $fillable = ['id'];
}
