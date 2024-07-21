<?php

namespace App\TaskManagement;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;

class TaskComment extends Model
{
    use HasFactory;
    use LogsActivity;
    protected $table = 'task_comment';
    protected $fillable = [
        'task_id',
        'content',
        'nik',
        'attachments',
        'id'
    ];
}
