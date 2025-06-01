<?php

route::middleware(['auth:sanctum'])->group(function () {
    // History endpoints
    Route::get('/history', [\App\Http\Controllers\Api\HistoryController::class, 'index']);
    Route::get('/history/{id}', [\App\Http\Controllers\Api\HistoryController::class, 'show']);
});