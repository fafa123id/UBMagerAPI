<?php


use App\Http\Controllers\Api\OrderController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:api'])->group(function () {
    // Order endpoints
    Route::get('/orders/{id}/receipt', [OrderController::class,'downloadReceipt']);
    Route::get('/orders/{id}/finish', [OrderController::class, 'finishOrder']);
    Route::get('/orders', [OrderController::class, 'index'])->middleware('Seller');
    Route::get('/orders/{id}/process', [OrderController::class, 'proccessOrder'])->middleware('Seller');
});