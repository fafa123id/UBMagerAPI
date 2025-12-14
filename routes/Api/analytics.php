<?php

use Illuminate\Support\Facades\Route;

Route::get('/analytics', [\App\Http\Controllers\Api\AnalyticsController::class, 'index']);
Route::get('/analytics/profile', [\App\Http\Controllers\Api\AnalyticsController::class, 'indexProfile'])->middleware('auth:api');