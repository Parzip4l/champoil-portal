<?php

namespace App\TaskManagement;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;

class Workspace extends Model
{
    use HasFactory;
    use LogsActivity;
    protected $table = 'task_workspace';

    protected $fillable = ['id','name','parent_id','owner','company','visibility'];

    public function getBreadcrumb()
    {
        $breadcrumb = [];
        $currentFolder = $this;

        // Traverse up the folder hierarchy
        while ($currentFolder) {
            array_unshift($breadcrumb, (object)[
                'name' => $currentFolder->name,
                'url' => route('workspace.show', $currentFolder->id)
            ]);
            $currentFolder = $currentFolder->parent;
        }

        // Prepend "Document Controls" to the breadcrumb array
        array_unshift($breadcrumb, (object)[
            'name' => 'Task Management',
            'url' => route('task-management.index')
        ]);

        return $breadcrumb;
    }

    public function subWorkspace()
    {
        return $this->hasMany(Workspace::class, 'parent_id');
    }
}
