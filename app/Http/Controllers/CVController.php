<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCVRequest;
use App\Models\CV;
use App\Services\FileUploadService;
use Illuminate\Support\Facades\Storage as FacadesStorage;

use function PHPUnit\Framework\returnSelf;

class CVController extends Controller
{
    public function index() {
        $cvs = CV::latest()->get();

        return response()->json([
            'status' => 'success',
            'data' => $cvs
        ]);
    }

    public function store(StoreCVRequest $request, FileUploadService $fileUploadService)   {

        $validated = $request->validated();

        $file = $request->file('cv_file');
        $uploadResult = $fileUploadService->uploadCV($file);

        $cv = CV::create([
            'title' => $validated['title'],
            'file_path' => $uploadResult['path'],
            'file_name' => $file->getClientOriginalName(),
            'mime_type' => $file->getMimeType(),
            'file_size' => $file->getSize(),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'CV uploaded successfully',
            'data' => $cv
        ], 201);
    }

    public function show(CV $cv) {
        return response()->json([
            'status' => 'success',
            'data' => $cv,
        ]);
    }

    public function destroy(CV $cv) {

        FacadesStorage::disk('public')->delete($cv->file_path);

        $cv->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'CV deleted successfully'
        ]);
    }
}
