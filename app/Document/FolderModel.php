<?php

namespace App\Document;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;

class FolderModel extends Model
{
    use HasFactory;
    use LogsActivity;
    protected $table = 'document_folder';
    protected $fillable = ['name', 'company','parent_id'];

    public function files()
    {
        return $this->hasMany(FileModel::class);
    }

    public function parent()
    {
        return $this->belongsTo(FolderModel::class, 'parent_id');
    }

    public function subfolders()
    {
        return $this->hasMany(FolderModel::class, 'parent_id');
    }

    public function getBreadcrumb()
    {
        $breadcrumb = [];
        $currentFolder = $this;

        // Traverse up the folder hierarchy
        while ($currentFolder) {
            array_unshift($breadcrumb, (object)[
                'name' => $currentFolder->name,
                'url' => route('folders.show', $currentFolder->id)
            ]);
            $currentFolder = $currentFolder->parent;
        }

        // Prepend "Document Controls" to the breadcrumb array
        array_unshift($breadcrumb, (object)[
            'name' => 'Document Controls',
            'url' => route('folders.index')
        ]);

        return $breadcrumb;
    }
}
