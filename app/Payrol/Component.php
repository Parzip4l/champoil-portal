<?php

namespace App\Payrol;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Component extends Model
{
    use HasFactory;
    protected $table = 'components';
    protected $fillable = ['name','type'];
}
