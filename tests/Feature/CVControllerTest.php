<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CVControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_upload_cv()
    {
        // Create a fake storage disk
        Storage::fake('public');

        // Create a fake PDF file
        $file = UploadedFile::fake()->create('resume.pdf', 1000, 'application/pdf');

        // Make request to upload CV
        $response = $this->postJson('/api/cvs', [
            'title' => 'My Resume',
            'cv_file' => $file,
        ]);

        // Assert response
        $response->assertStatus(201)
                ->assertJsonStructure([
                    'status',
                    'message',
                    'data' => [
                        'id',
                        'title',
                        'file_path',
                        'mime_type',
                        'file_size',
                        'created_at',
                        'updated_at',
                    ]
                ]);

        // Assert file was stored
        $cv = \App\Models\CV::first();
        Storage::disk('public')->exists($cv->file_path);
        $this->assertTrue(Storage::disk('public')->exists($cv->file_path));
    }

    public function test_cv_upload_validation(): void
    {
        // Test file type validation
        $file = UploadedFile::fake()->create('document.txt', 100, 'text/plain');

        $response = $this->postJson('/api/cvs', [
            'title' => 'My Resume',
            'cv_file' => $file,
        ]);

        // Assert validation errors for file type
        $response->assertStatus(422)
                ->assertJsonValidationErrors(['cv_file']);
    }

    // Additional tests for listing, viewing, and deleting CVs...
}
