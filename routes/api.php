<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::prefix("azure")->name("api_image.")->group(function () {
    Route::delete('/delete', [\App\Http\Controllers\AzureController::class, "imageDelete"])->name("delete");
    Route::post("/image", [\App\Http\Controllers\AzureController::class, "imageUploadUpdateStorage"])->name("update");
    Route::get("/get", [\App\Http\Controllers\AzureController::class, "ajaxImageGet"])->name("get");
});
