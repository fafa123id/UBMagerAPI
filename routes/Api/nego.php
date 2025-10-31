<?php
use Illuminate\Support\Facades\Route;
Route::middleware(['auth:api'])->group(function () {
    // Nego for user
    Route::post('nego', [\App\Http\Controllers\Api\NegoController::class, 'requestNego']);
    Route::get('nego/cancel/{id}', [\App\Http\Controllers\Api\NegoController::class, 'cancelNego']);
    Route::get('nego', [\App\Http\Controllers\Api\NegoController::class, 'myNegos']);
    Route::get('nego/{id}', [\App\Http\Controllers\Api\NegoController::class, 'negoDetail']);
    Route::middleware(['Seller'])->group(function () {
        // Nego for seller
        Route::get('nego-seller', [\App\Http\Controllers\Api\NegoController::class, 'sellerAll']);
        Route::get('nego-seller/{id}', [\App\Http\Controllers\Api\NegoController::class, 'show']);
        Route::get('nego/decline/{id}', [\App\Http\Controllers\Api\NegoController::class, 'declineNego']);
        Route::get('nego/accept/{id}', [\App\Http\Controllers\Api\NegoController::class, 'acceptNego']);
    });
});