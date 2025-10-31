<?php
use App\Http\Controllers\Api\userController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:api'])->group(function(){
    Route::get('/user', [userController::class, 'index']);
    Route::get('/user/{id}', [userController::class, 'show']);
    Route::put('/user/{id}', [userController::class, 'update']);
});