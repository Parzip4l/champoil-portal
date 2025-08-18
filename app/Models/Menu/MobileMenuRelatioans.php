<?php

namespace App\Models\Menu;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MobileMenuRelatioans extends Model
{
    use HasFactory;

    protected $fillable = [
        'mobile_menu_id',
        'company_name'
    ];
}
