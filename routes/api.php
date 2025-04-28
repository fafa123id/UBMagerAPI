<?php

use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\Auth\OtpSenderController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->group(function(){
    Route::get('/user', function (Request $request) {
        return request()->user();
    });
    Route::apiResource('cart', CartController::class);
    Route::post('/cart/{id}/add-one', [CartController::class, 'addOne']);
    Route::post('/cart/{id}/remove-one', [CartController::class, 'removeOne']);
    Route::delete('/cart', [CartController::class, 'destroyAll']);
    //For Seller
    Route::middleware(['Seller'])->group(function(){
        Route::apiResource('product',ProductController::class)->except(['show','index']);
    });
});
//guest
Route::get('/product/{id}', [ProductController::class, 'show']);
Route::get('/product', [ProductController::class, 'index']);
Route::get('/product-type', [ProductController::class, 'getType']);
Route::get('/product-category/{type}', [ProductController::class, 'getCategoryByType']);



require __DIR__.'/auth.php';