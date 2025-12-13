<?php

use App\Http\Controllers\Api\RatingController;
use App\Models\Rating;
use Illuminate\Support\Facades\Route;
Route::post('/rating/{id}', [RatingController::class ,'store'])
    ->middleware('auth:api');
Route::get('/rating/{id}', [RatingController::class ,'get']);
Route::get('/rating/page-count/{id}', [RatingController::class ,'pageCount']);
Route::get('/rating/count/{id}', [RatingController::class ,'count']);
Route::get('/seller-rating/{id}',[RatingController::class ,'getSellerRating']); 