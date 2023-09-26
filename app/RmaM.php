<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RmaM extends Model
{
    use HasFactory;
    protected $table = 'rma_m_s';
    protected $fillable = ['id'];
}
