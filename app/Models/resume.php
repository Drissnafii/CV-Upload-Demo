<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class resume extends Model
{
    /** @use HasFactory<\Database\Factories\ResumeFactory> */
    use HasFactory;

    protected $table = 'resumes';
    protected $fillable = [
        "title",
        "file_path",
        "file_size",
        "file_name"
        ];

    public function user()   {
        return $this->belongsTo(User::class);
    }
}
