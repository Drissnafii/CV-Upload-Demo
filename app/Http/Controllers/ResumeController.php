<?php

namespace App\Http\Controllers;

use App\Models\resume;
use App\Http\Requests\StoreresumeRequest;
use App\Http\Requests\UpdateresumeRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;  // Str

class ResumeController extends Controller
{

    public function getToken(){
        return csrf_token();
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try{
            $validator = $request->validate([
                "title"=> "required|string|max:255",
                "cv_file"=> "required|file|mimes:pdf",
                "user_id"=> "required",
            ]);
        }catch(\Exception $e){
            return "erro" . $e->getMessage();
        }

        $file = $request->file('cv_file');
        $fileName = time() . '_' . Str::slug($request->title) . '.pdf';

        // Store file locally
        $filePath = $file->storeAs('cvs/' . Auth::id(), $fileName, 'public');

        $cv = resume::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'file_path' => $filePath,
            'file_name' => $fileName,
            'file_size' => $file->getSize()
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'CV uploaded successfully',
            'data' => $cv
        ], 201);
    }

}
