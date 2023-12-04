<?php

namespace App\Absen;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestType extends Model
{
    use HasFactory;
    protected $table = 'request_types';
    protected $fillable = ['code','name','company'];
}
