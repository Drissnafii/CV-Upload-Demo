<?php

namespace App\Models;

use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CV extends Model
{
    use HasFactory;

    protected $table = "cvs";
    protected $fillable = [
        "title",
        "file_name",
        "file_size",
        "file_path",
        "mime_type"
        ];

    protected $appends = [
        "file_url"
        ];

    public function getFileUrlAttribute() {
        return Storage::url($this->file_path);
    }
}
