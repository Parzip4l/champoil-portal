<?php

namespace App\Http\Controllers\Api\Document;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

use App\Document\FolderModel;
use App\Document\FileModel;
use App\Models\Document\Recent;
use App\Models\Document\Tags;
use App\Employee;
use App\User;
use App\ModelCG\Project;

class DocumentController extends Controller
{
    public function index($id,Request $request)
    {
        $company = User::where('id', $id)->first();

        // Retrieve folders based on company unit_bisnis and optional search query
        $query = FolderModel::where('company', $company->company);

        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $folders = $query->get();

        // Initialize an array to store file counts
        $fileCounts = [];

        // Iterate through each folder and count the files
        foreach ($folders as $folder) {
            
            $folder->name = strtoupper($folder->name);
            $fileCounts[$folder->id] = FileModel::where('folder_id', $folder->id)->count();
            $folder->tags = $this->tags($folder->id);
        }

        $recentFile = FileModel::where('company', $company->unit_bisnis)
                                ->orderBy('created_at', 'desc')
                                ->limit(10)
                                ->get();

        // Return folders, file counts, and recent files as JSON response
        return response()->json([
            'folders' => $folders,
            'fileCounts' => $fileCounts,
            'recentFiles' => $recentFile,
        ]);
    }

    public function rootFolder(Request $request)
    {
        $company = User::where('id', $request->user_id)->first();

        $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable',
        ]);

        $folder = FolderModel::create([
            'name' => $request->name,
            'parent_id' => $request->parent_id ?? null,
            'company' => $company->company,
        ]);

        $folder->name = strtoupper($folder->name);

        return response()->json([
            'id' => $folder->id,
            'name' => $folder->name,
            'company' => $folder->company,
            'created_at' => $folder->created_at,
            'updated_at' => $folder->updated_at,
            'parent_id' => $folder->parent_id,
        ], 201);
    }

    public function uploadFiles(Request $request)
    {
        $company = User::where('id', $request->user_id)->first();

        $request->validate([
            'documentFile' => 'required|file',
            'documentName' => 'required|string|max:255',
            'useDueDate' => 'required|in:yes,no',
            'folderId' => 'required|integer|exists:document_folder,id',
        ]);

        if ($request->hasFile('documentFile')) {
            $file = $request->file('documentFile');
            $path = $file->store('documents', 'public'); // Simpan di storage/app/public/documents

            $fileRecord = FileModel::create([
                'folder_id' => $request->folderId,
                'path' => $path, // Path relatif ke storage/app/public
                'name' => $request->documentName,
                'due_date' => $request->useDueDate === 'yes' ? $request->dueDate : null,
                'reminder' => $request->reminder,
                'company' => $company->company,
                'uploader' => $request->user_id,
            ]);

            // Save to Recent model
            Recent::create([
                'file_id' => $fileRecord->id,
                'user_id' => $request->user_id,
            ]);
        }

        return response()->json(['message' => 'Files uploaded successfully'], 201);
    }

    public function recent($id){
        $company = User::where('id', $id)->first();
        

        $recentFiles = Recent::join('document_files', 'recents.file_id', '=', 'document_files.id')
                            ->where('document_files.company', $company->company)
                            ->orderBy('recents.created_at', 'desc')
                            ->select('document_files.*', 'recents.created_at as recent_created_at')
                            ->limit(10)
                            ->get();
        if($recentFiles->isEmpty()){
            return response()->json(['message' => 'No recent files found'], 404);
        }

        foreach ($recentFiles as $file) {
            $file->full_url = asset('storage/' . $file->path); // Gunakan asset() untuk URL publik
            $file->uploader = User::join('karyawan','karyawan.nik','=','users.name')->where('users.id', $file->uploader)->value('nama'); // Ambil nama uploader

            if ($file->due_date) {
                $remainingDays = (new \DateTime($file->due_date))->diff(new \DateTime())->days;
                $file->remaining = strtotime($file->due_date) > time() 
                    ? $remainingDays . ' days remaining' 
                    : 'Past due';

                if (strtotime($file->due_date) > time()) {
                    if ($remainingDays <= 30) {
                        $file->color = "text-danger";
                    } elseif ($remainingDays > 30 && $remainingDays < 60) {
                        $file->color = "text-warning";
                    } else {
                        $file->color = "text-success";
                    }
                }

                $file->due_date = date('l, d M Y', strtotime($file->due_date));
            } else {
                $file->remaining = null;
                $file->color = null;
                $file->due_date = null;
            }
        }

        return response()->json($recentFiles);
    }

    public function filesList($id){
        $files = FileModel::where('folder_id', $id)->get();

        foreach ($files as $file) {
            $file->full_url = asset('storage/' . $file->path); // Gunakan asset() untuk URL publik
            $file->uploader = User::join('karyawan','karyawan.nik','=','users.name')->where('users.id', $file->uploader)->value('nama'); // Ambil nama uploader

            if ($file->due_date) {
                $remainingDays = (new \DateTime($file->due_date))->diff(new \DateTime())->days;
                $file->remaining = strtotime($file->due_date) > time() 
                    ? $remainingDays . ' days remaining' 
                    : 'Past due';

                if (strtotime($file->due_date) > time()) {
                    if ($remainingDays <= 30) {
                        $file->color = "text-danger";
                    } elseif ($remainingDays > 30 && $remainingDays < 60) {
                        $file->color = "text-warning";
                    } else {
                        $file->color = "text-success";
                    }
                }

                $file->due_date = date('l, d M Y', strtotime($file->due_date));
            } else {
                $file->remaining = null;
                $file->color = null;
                $file->due_date = null;
            }
        }

        return response()->json($files);
    }


    public function tags($id){
        $tags = Tags::where('folder_id', $id)->get();
        if($id ==28){
            $tags = Project::all();
        }
        return $tags;
    }

    public function addTags(Request $request){
        $request->validate([
            'folder_id' => 'required|integer|exists:document_folder,id',
            'tags' => 'required|string|max:255',
        ]);

        $tag = Tags::create([
            'folder_id' => $request->folder_id,
            'tags' => $request->tags,
        ]);

        return response()->json([
            'id' => $tag->id,
            'folder_id' => $tag->folder_id,
            'tags' => $tag->tags,
            'created_at' => $tag->created_at,
            'updated_at' => $tag->updated_at,
        ], 201);
    }


}
