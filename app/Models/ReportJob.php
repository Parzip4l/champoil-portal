<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportJob extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'project_name',
        'params',
        'file_paths',
        'status',
        'error_message',
    ];
}
