<?php
use Illuminate\Support\Facades\Route;
Route::middleware(['auth:api'])->group(function () {
    // History endpoints
    Route::get('/history', [\App\Http\Controllers\Api\HistoryController::class, 'index']);
    Route::get('/history/{id}', [\App\Http\Controllers\Api\HistoryController::class, 'show']);
});