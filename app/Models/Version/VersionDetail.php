<?php

namespace App\Models\Version;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VersionDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'app_name',
        'platform_id',
        'version_code',
        'version_name',
        'changelog',
        'release_type',
        'download_url',
        'released_at',
    ];
}
