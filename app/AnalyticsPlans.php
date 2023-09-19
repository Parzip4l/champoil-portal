<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnalyticsPlans extends Model
{
    use HasFactory;
    protected $table = 'analytics_plans';
    protected $keyType = 'string'; // Menentukan tipe kunci sebagai string (UUID)
    public $incrementing = false; // Non-incrementing UUID
    protected $fillable = ['id','name', 'applicability'];
}
