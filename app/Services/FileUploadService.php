<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class FileUploadService
{
    /**
     * Upload a CV file to storage.
     *
     * @param UploadedFile $file
     * @return array
     */
    public function uploadCV(UploadedFile $file): array
    {
        // Generate a unique filename
        $filename = time() . '_' . Str::random(length: 10) . '.' . $file->getClientOriginalExtension();

        // Define the storage path
        $path = "cvs";

        // Store the file
        $filePath = $file->storeAs($path, $filename, 'public');

        return [
            'path' => $filePath,
            'filename' => $filename,
        ];
    }
}
