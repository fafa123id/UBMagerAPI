<?php


use App\Http\Controllers\Api\OrderController;

route::middleware(['auth:sanctum','Seller'])->group(function () {
    // Order endpoints
    Route::get('/orders', [OrderController::class, 'index']);
});