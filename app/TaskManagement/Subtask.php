<?php

namespace App\TaskManagement;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;

class Subtask extends Model
{
    use HasFactory;
    use LogsActivity;
    protected $table = 'task_subtasks';

    protected $fillable = ['task_id', 'title', 'description', 'due_date', 'progress','status'];


    // Tentukan bahwa primary key adalah UUID
    protected $primaryKey = 'id';

    // Tentukan bahwa primary key adalah UUID versi 4
    protected $keyType = 'string';

    // Nonaktifkan incrementing karena UUID tidak menggunakan auto-increment
    public $incrementing = false;

    // Atur secara otomatis nilai UUID saat membuat instance model baru
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id = (string) \Illuminate\Support\Str::uuid();
        });
    }

    public function task()
    {
        return $this->belongsTo(TaskMaster::class, 'task_id');
    }
}
