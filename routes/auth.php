<?php

use App\Http\Controllers\Api\userController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\OtpSenderController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;

Route::post('/verify-email/send', [OtpSenderController::class, 'otpVerifySend'])->middleware(['unverified','auth:api']);
Route::post('/verify-email', [VerifyEmailController::class, 'verifyEmail'])->middleware(['unverified','auth:api']);
Route::post('/new-password', [ResetPasswordController::class, 'newPassword'])->middleware(['auth:api']);
Route::post('/forgot-password', [OtpSenderController::class, 'otpResetSend'])->middleware(['verified']);
Route::post('/forgot-password/verify', [ResetPasswordController::class, 'verifyOtp'])->middleware(['verified']);
Route::post('/reset-password', [ResetPasswordController::class, 'resetPassword'])->middleware(['verified']);
Route::post('/register', [RegisteredUserController::class, 'store'])
    ->name('register');

Route::post('/login', [AuthenticatedSessionController::class, 'store'])
    ->name('login');

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth:api')
    ->name('logout');

Route::get('/be-mitra',[userController::class,'changeRole'])->middleware(['auth:api']);