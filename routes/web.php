<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('/', [\App\Http\Controllers\AzureController::class, "images"])->name("get");
Route::prefix("azure")->name("image.")->group(function () {
    Route::get("/upload", [\App\Http\Controllers\AzureController::class, "imageUpload"])->name("upload");
    Route::post("/uploadStorage", [\App\Http\Controllers\AzureController::class, "imageUploadStorage"])->name("uploadStorage");
});


Route::get("/survey", [App\Http\Controllers\Surveys::class, "index"]);
Route::get("/survey/{id}", [App\Http\Controllers\Surveys::class, "list"]);

