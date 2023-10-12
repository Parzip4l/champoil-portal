<?php

namespace App\Absen;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestAbsen extends Model
{
    use HasFactory;
    protected $table = 'requests_attendence';
    protected $fillable = ['employee'];
}
