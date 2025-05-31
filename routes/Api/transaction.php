<?php
use App\Http\Controllers\Api\CheckoutController;
Route::middleware('auth:sanctum')->group(function () {
    // Checkout endpoints
    Route::post('/checkout', [CheckoutController::class, 'checkout']);
    Route::get('/transactions', [CheckoutController::class, 'getUserTransactions']);
    Route::get('/transaction/{receipt}', [CheckoutController::class, 'getTransactionStatus']);
});
// Midtrans callback (no auth required)
Route::post('/midtrans/notification', [CheckoutController::class, 'handleNotification']);