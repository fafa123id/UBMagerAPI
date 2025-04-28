<?php
use App\Http\Controllers\Api\userController;

Route::middleware(['auth:sanctum'])->group(function(){
    Route::get('/user', [userController::class, 'index']);
    Route::get('/user/{id}', [userController::class, 'show']);
    Route::put('/user/{id}', [userController::class, 'update']);
    Route::delete('/user/{id}', [userController::class,'delete']);
});