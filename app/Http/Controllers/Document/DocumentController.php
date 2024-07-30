<?php

namespace App\Http\Controllers\Document;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

use App\Document\FolderModel;
use App\Document\FileModel;
use App\Employee;


class DocumentController extends Controller
{
    public function indexFolder(Request $request)
    {
        $code = Auth::user()->employee_code;
        $company = Employee::where('nik', $code)->first();

        // Retrieve folders based on company unit_bisnis and optional search query
        $query = FolderModel::where('company', $company->unit_bisnis)
                            ->where('parent_id', null);

        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $folders = $query->get();

        // Initialize an array to store file counts
        $fileCounts = [];

        // Iterate through each folder and count the files
        foreach ($folders as $folder) {
            $fileCounts[$folder->id] = FileModel::where('folder_id', $folder->id)->count();
        }

        $recentFile = FileModel::where('company', $company->unit_bisnis)
                                ->orderBy('created_at', 'desc')
                                ->limit(10)
                                ->get();

        // Pass folders and file counts to the view
        return view('pages.document.index', compact('folders', 'fileCounts','recentFile'));
    }


    public function storeFolder(Request $request)
    {
        $code = Auth::user()->employee_code;
        $company = Employee::where('nik', $code)->first();

        $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable',
        ]);

        FolderModel::create([
            'name' => $request->name,
            'parent_id' => $request->parent_id,
            'company' => $company->unit_bisnis
        ]);

        return redirect()->back()->with('success', 'Folder berhasil Dibuat');;
    }

    public function storeFiles(Request $request, $folderId)
    {
        $company = Auth::user()->company;
        $code = Auth::user()->employee_code;

        $request->validate([
            'file.*' => 'required|max:2048',
        ]);

        try {
            $folder = FolderModel::findOrFail($folderId);

            if ($request->hasFile('file')) {
                $files = $request->file('file');

                foreach ($files as $file) {
                    $path = $file->store("files/{$company}/{$folderId}");

                    FileModel::create([
                        'name' => $file->getClientOriginalName(),
                        'path' => $path,
                        'folder_id' => $folderId,
                        'company' => $company,
                        'uploader' => $code
                    ]);
                }
            }

            return response()->json(['message' => 'Files uploaded successfully'], 200);
        } catch (\Exception $e) {
            Log::error('File upload failed:', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'File upload failed'], 500);
        }
    }



    public function showFolder(FolderModel $folder)
    {
        // Get all files in the folder
        $files = FileModel::where('folder_id', $folder->id)->get();

        // Get breadcrumb trail
        $breadcrumb = $folder->getBreadcrumb();

        return view('pages.document.show', compact('folder', 'files', 'breadcrumb'));
    }

    public function updateFolder(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $folder = FolderModel::findOrFail($id);
        $folder->name = $request->name;
        $folder->save();

        return redirect()->back()->with('success', 'Folder renamed successfully.');
    }

    public function deleteFolder($id)
    {
        $folder = FolderModel::findOrFail($id);
        $subfolder = FolderModel::where('parent_id',$id)->delete();
        $files = FileModel::where('folder_id',$id)->delete();
        $folder->delete();

        return redirect()->back()->with('success', 'Folder deleted successfully.');
    }

    public function download($id)
    {
        // Temukan file berdasarkan ID
        $file = FileModel::findOrFail($id);

        // Dapatkan path file dari model
        $filePath = storage_path('app/' . $file->path); // Sesuaikan dengan path file Anda

        // Periksa apakah file ada
        if (!file_exists($filePath)) {
            abort(404, 'File not found.');
        }

        // Kembalikan response download
        return response()->download($filePath, $file->name);
    }

    public function deleteFile($id)
    {
        try {
            $file = FileModel::findOrFail($id);
            // Delete file from database
            $file->delete();

            return redirect()->back()->with('success', 'File deleted successfully.');
        } catch (\Exception $e) {
            \Log::error('File delete error:', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'An error occurred while deleting the file.');
        }
    }

}
