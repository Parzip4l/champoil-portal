<?php

namespace App\ModelCG;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class List_task extends Model
{
    use HasFactory;
    protected $connection = 'mysql_secondary';
    protected $table = 'list_task';

    /**
     * Get the patrol records associated with the list task.
     */
    public function list()
    {
        // Define the relationship with Patroli
        return $this->hasMany(Patroli::class, 'unix_code');
    }
}
