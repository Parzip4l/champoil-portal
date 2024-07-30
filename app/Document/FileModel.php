<?php

namespace App\Document;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;

class FileModel extends Model
{
    use HasFactory;
    use LogsActivity;
    protected $table = 'document_files';
    protected $fillable = ['name', 'path', 'folder_id','company','uploader'];

    public function files()
    {
        return $this->belongsTo(FolderModel::class);
    }
}
