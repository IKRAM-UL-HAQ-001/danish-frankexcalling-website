<?php

namespace App\Http\Controllers;

use App\Models\uploadFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\YourFileImport;

class UploadFileController extends Controller
{
    public function index()
    {
        $Files = uploadFile::orderBy('created_at', 'desc')->get(); 
        return view('admin.upload_files.list', compact('Files'));
    }

    public function getFile(Request $request)
    {
        $filePath = $request->input('file_path');
        if (!Storage::disk('public')->exists($filePath)) {
            return response()->json(['error' => 'File not found'], 404);
        }
        return Storage::disk('public')->get($filePath);
    }


    public function store(Request $request)
    {
        $request->validate([
            'files.*' => 'required|mimes:xlsx,xls,csv|max:2048',
        ]);

        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $fileName = time() . '-' . $file->getClientOriginalName();
                $filePath = $file->storeAs('uploads', $fileName, 'public');

                uploadFile::create([
                    'file_name' => $fileName,
                    'file_path' => $filePath,
                    'uploaded_at' => now(),
                ]);
            }
        }

        return redirect()->back()->with('success', 'Files uploaded successfully!');
    }


    public function destroy(Request $request)
    {
        $file = uploadFile::findOrFail($request->id);

        if (Storage::disk('public')->exists($file->file_path)) {
            Storage::disk('public')->delete($file->file_path);
        }

        // Delete file record from the database
        $file->delete();

        return redirect()->back()->with('success', 'File deleted successfully!');
    }

    public function displayFileData(Request $request)
    {
        $fileId = $request->input('id'); 
    $file = uploadFile::find($fileId); 
    
    $filePath = storage_path('app/public/' . $file->file_path); 
    
    $data = Excel::toArray(new YourFileImport, $filePath); 

    $fileData = $data[0]; 
    return response()->json(['fileData' => $fileData]);
    }
}
