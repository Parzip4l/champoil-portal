<?php

namespace App\TaskManagement;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;

class TaskMaster extends Model
{
    use HasFactory;
    use LogsActivity;
    protected $table = 'task_master';

    protected $fillable = [
        'title',
        'description',
        'due_date',
        'progress',
        'attachments',
        'status',
        'company',
        'priority'
    ];

    // Tentukan bahwa primary key adalah UUID
    protected $primaryKey = 'id';

    // Tentukan bahwa primary key adalah UUID versi 4
    protected $keyType = 'string';

    // Nonaktifkan incrementing karena UUID tidak menggunakan auto-increment
    public $incrementing = false;

    public function assignedUsers()
    {
        return $this->hasMany(TaskUser::class, 'task_id');
    }
}
