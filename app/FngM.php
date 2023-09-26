<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FngM extends Model
{
    use HasFactory;
    protected $table = 'fng_m_s';
    protected $fillable = ['id'];
}
