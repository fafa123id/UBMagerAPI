<?php

use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\OTP\OtpController;
use App\Http\Controllers\Webhook\TwilioController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->group(function(){
    Route::get('/user', function (Request $request) {
        return request()->user();
    });
    Route::post('/otp/send', [OtpController::class, 'sendSMS'])->middleware(['throttle:2,1', 'unverified']);
    Route::post('/otp/verify', [OtpController::class, 'verifySMS'])->middleware(['unverified']);
    //For Seller
    Route::middleware(['Seller'])->group(function(){
        Route::apiResource('product',ProductController::class)->except(['show','index']);
    });
});
//guest
Route::get('/product/{id}', [ProductController::class, 'show']);
Route::get('/product', [ProductController::class, 'index']);


require __DIR__.'/auth.php';