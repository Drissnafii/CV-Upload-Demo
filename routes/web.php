<?php

use App\Http\Controllers\ResumeController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::post('/cvs', [ResumeController::class , "store"]);
Route::get('/getToken', [ResumeController::class , "getToken"]);
