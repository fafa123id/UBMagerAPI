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
    Route::post('/verify-email/send', [OtpSenderController::class, 'otpVerifySend'])->middleware(['throttle:2,1', 'unverified']);
    Route::post('/verify-email', [VerifyEmailController::class, 'verifyEmail'])->middleware(['unverified']);
    Route::post('/update-password', [ResetPasswordController::class, 'newPassword']);
    Route::apiResource('cart', CartController::class);
    Route::post('/cart/{id}/add-one', [CartController::class, 'addOne']);
    Route::post('/cart/{id}/remove-one', [CartController::class, 'removeOne']);
    //For Seller
    Route::middleware(['Seller'])->group(function(){
        Route::apiResource('product',ProductController::class)->except(['show','index']);
    });
});
//guest
Route::get('/product/{id}', [ProductController::class, 'show']);
Route::get('/product', [ProductController::class, 'index']);
Route::get('/type', [ProductController::class, 'getType']);
Route::get('/category/{type}', [ProductController::class, 'getCategoryByType']);
Route::post('/forgot-password', [OtpSenderController::class, 'otpResetSend'])->middleware(['throttle:2,1', 'verified']);
Route::post('/forgot-password/verify', [ResetPasswordController::class, 'verifyOtp'])->middleware(['verified']);
Route::post('/reset-password', [ResetPasswordController::class, 'resetPassword'])->middleware(['verified']);


require __DIR__.'/auth.php';