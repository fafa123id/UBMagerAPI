<?php

route::middleware(['auth:sanctum'])->group(function () {
    // Nego for user
    route::post('nego', [\App\Http\Controllers\Api\NegoController::class, 'requestNego']);
    route::get('nego/cancel/{id}', [\App\Http\Controllers\Api\NegoController::class, 'cancelNego']);
    route::get('nego', [\App\Http\Controllers\Api\NegoController::class, 'myNegos']);
    route::get('nego/{id}', [\App\Http\Controllers\Api\NegoController::class, 'negoDetail']);
    route::middleware(['Seller'])->group(function () {
        // Nego for seller
        route::get('nego-seller', [\App\Http\Controllers\Api\NegoController::class, 'sellerAll']);
        route::get('nego-seller/{id}', [\App\Http\Controllers\Api\NegoController::class, 'show']);
        route::get('nego/decline/{id}', [\App\Http\Controllers\Api\NegoController::class, 'declineNego']);
        route::get('nego/accept/{id}', [\App\Http\Controllers\Api\NegoController::class, 'acceptNego']);
    });
});