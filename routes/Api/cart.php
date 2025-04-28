<?php
use App\Http\Controllers\Api\CartController;

Route::middleware(['auth:sanctum'])->group(function(){
    Route::apiResource('cart', CartController::class);
    Route::post('/cart/{id}/add-one', [CartController::class, 'addOne']);
    Route::post('/cart/{id}/remove-one', [CartController::class, 'removeOne']);
    Route::delete('/cart', [CartController::class, 'destroyAll']);
});