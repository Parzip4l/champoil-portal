<?php

namespace App\Setting\Features;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeaturesModel extends Model
{
    use HasFactory;
    protected $table = 'features';
    protected $fillable = [
        'title', 'icon', 'url', 'parent_id', 'is_active', 'order','roles'
    ];

    protected $casts = [
        'roles' => 'array',
    ];

    public function children()
    {
        return $this->hasMany(FeaturesModel::class, 'parent_id')->orderBy('order');
    }

    public function companyFeatures(): HasMany
    {
        return $this->hasMany(CompanyFeatures::class, 'feature_id');
    }
}
