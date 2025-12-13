<?php

use Illuminate\Support\Facades\Route;

Route::middleware('auth:api')->group(function () {
    Route::get('/favorites', [App\Http\Controllers\Api\FavoriteController::class, 'index']);
    Route::get('/favorites/page-count', [App\Http\Controllers\Api\FavoriteController::class, 'countPage']);
    Route::post('/favorites', [App\Http\Controllers\Api\FavoriteController::class, 'store']);
    Route::delete('/favorites/{id}', [App\Http\Controllers\Api\FavoriteController::class, 'destroy']);
});