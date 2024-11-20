<?php

namespace App\ModelCG;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;
    protected $table = 'master_tasks';

    /**
     * Get the points associated with the task.
     */
    public function point()
    {
        // Define the relationship with List_task
        return $this->hasMany(List_task::class, 'id_master'); 
    }
}
