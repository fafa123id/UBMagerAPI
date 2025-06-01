<?php

use App\Http\Controllers\Api\RatingController;
use App\Models\Rating;

route::post('/rating/{id}', [RatingController::class ,'store'])
    ->middleware('auth:sanctum');