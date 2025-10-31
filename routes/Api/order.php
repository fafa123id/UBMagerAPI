<?php


use App\Http\Controllers\Api\OrderController;

route::middleware(['auth:api'])->group(function () {
    // Order endpoints
    Route::get('/orders/{id}/finish', [OrderController::class, 'finishOrder']);
    Route::get('/orders', [OrderController::class, 'index'])->middleware('Seller');
    Route::get('/orders/{id}/process', [OrderController::class, 'proccessOrder'])->middleware('Seller');
});