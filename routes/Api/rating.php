<?php

use App\Http\Controllers\Api\RatingController;
use App\Models\Rating;
use Illuminate\Support\Facades\Route;
Route::post('/rating/{id}', [RatingController::class ,'store'])
    ->middleware('auth:api');