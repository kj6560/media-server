<?php

use App\Http\Controllers\Api\FileController;

use Illuminate\Support\Facades\Route;

Route::post('/storeFile', [FileController::class,'storeFile'])->name("storeFile");
Route::get('/index', [FileController::class,'index']);
Route::get('/deleteFile', [FileController::class,'deleteFile']);