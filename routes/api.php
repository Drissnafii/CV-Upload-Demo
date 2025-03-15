<?php

use App\Http\Controllers\CVController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource('cvs', CVController::class)->except('update');

// GET    /api/cvs              - index   - List all CVs
// POST   /api/cvs              - store   - Create a new CV
// GET    /api/cvs/{cv}         - show    - Get a specific CV
// DELETE /api/cvs/{cv}         - destroy - Delete a specific CV
