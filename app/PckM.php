<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PckM extends Model
{
    use HasFactory;
    protected $table = 'pck_m_s';
    protected $fillable = ['id'];
}
