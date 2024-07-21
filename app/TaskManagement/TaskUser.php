<?php

namespace App\TaskManagement;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;

class TaskUser extends Model
{
    use HasFactory;
    use LogsActivity;
    protected $table = 'task_user';

    protected $fillable = ['id','task_id','nik'];
}
