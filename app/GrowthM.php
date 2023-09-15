<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GrowthM extends Model
{
    use HasFactory;
    protected $table = 'growth_team';
    protected $keyType = 'string'; // Menentukan tipe kunci sebagai string (UUID)
    public $incrementing = false; // Non-incrementing UUID
    protected $fillable = ['id','team_kode', 'team_name', 'monthly_targets', 'total_commission'];

    protected $casts = [
        'monthly_targets' => 'json',
    ];
}
