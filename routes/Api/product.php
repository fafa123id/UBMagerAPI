<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductController;

Route::middleware(['auth:api'])->group(function(){
    Route::middleware(['Seller'])->group(function(){
        Route::apiResource('product',ProductController::class)->except(['show','index']);
    });
});
Route::get('/product/{id}', [ProductController::class, 'show']);
Route::get('/product/is-favorited/{id}', [ProductController::class, 'isFavorited'])->middleware('auth:api');
Route::get('/product', [ProductController::class, 'index']);
Route::get('/product-page', [ProductController::class, 'getPageCount']);
Route::get('/product-type', [ProductController::class, 'getType']);
Route::get('/product-category/{type}', [ProductController::class, 'getCategoryByType']);
