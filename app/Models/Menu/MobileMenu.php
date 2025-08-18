<?php

namespace App\Models\Menu;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MobileMenu extends Model
{
    use HasFactory;

    protected $fillable = [
        'menu_name',
        'icon',
        'route_link',
        'urutan',
        'maintenance',
    ];
}
