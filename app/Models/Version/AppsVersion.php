<?php

namespace App\Models\Version;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppsVersion extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', // Nama platform (Android, iOS, Web)
        'code', // Kode unik (android, ios, web)
    ];
}
