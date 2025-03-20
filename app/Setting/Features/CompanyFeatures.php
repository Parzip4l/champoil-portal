<?php

namespace App\Setting\Features;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyFeatures extends Model
{
    use HasFactory;
    protected $fillable = ['company_id', 'feature_id', 'is_enabled'];
}
