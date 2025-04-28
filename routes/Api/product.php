<?php

use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\ProductController;

Route::middleware(['auth:sanctum'])->group(function(){
    Route::middleware(['Seller'])->group(function(){
        Route::apiResource('product',ProductController::class)->except(['show','index']);
    });
});
Route::get('/product/{id}', [ProductController::class, 'show']);
Route::get('/product', [ProductController::class, 'index']);
Route::get('/product-type', [ProductController::class, 'getType']);
Route::get('/product-category/{type}', [ProductController::class, 'getCategoryByType']);
