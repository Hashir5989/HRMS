<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\Folder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileController extends Controller
{
    public function index(Request $request)
    {
        $folderId = $request->get('folder');
        $currentFolder = $folderId ? Folder::findOrFail($folderId) : null;

        $folders = Folder::where('parent_id', $folderId)
            ->withCount('files')
            ->orderBy('name')
            ->get();

        $files = File::where('folder_id', $folderId)
            ->with('uploader')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $breadcrumbs = [];
        if ($currentFolder) {
            $folder = $currentFolder;
            while ($folder) {
                array_unshift($breadcrumbs, $folder);
                $folder = $folder->parent;
            }
        }

        return view('files.index', compact('folders', 'files', 'currentFolder', 'breadcrumbs'));
    }

    public function store(Request $request)
    {
        $this->authorize('file.upload');

        if ($request->has('create_folder')) {
            $request->validate(['folder_name' => 'required|string|max:255']);

            Folder::create([
                'name' => $request->folder_name,
                'parent_id' => $request->folder_id ?: null,
                'created_by' => auth()->id(),
            ]);

            return back()->with('success', 'Folder created successfully.');
        }

        $request->validate([
            'files' => 'required',
            'files.*' => 'file|max:20480',
        ]);

        foreach ($request->file('files') as $file) {
            $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('uploads', $filename, 'public');

            File::create([
                'name' => $filename,
                'original_name' => $file->getClientOriginalName(),
                'mime_type' => $file->getMimeType(),
                'size' => $file->getSize(),
                'path' => $path,
                'folder_id' => $request->folder_id ?: null,
                'uploaded_by' => auth()->id(),
            ]);
        }

        return back()->with('success', 'Files uploaded successfully.');
    }

    public function download(File $file)
    {
        $file->increment('downloads');
        $path = Storage::disk('public')->path($file->path);
        return response()->download($path, $file->original_name);
    }

    public function destroy(File $file)
    {
        $this->authorize('file.delete');
        Storage::disk('public')->delete($file->path);
        $file->delete();
        return back()->with('success', 'File deleted successfully.');
    }
}
