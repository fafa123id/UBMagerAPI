<?php

use App\Http\Controllers\Api\userController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\OtpSenderController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;

Route::post('/verify-email/send', [OtpSenderController::class, 'otpVerifySend'])->middleware(['unverified','auth:sanctum']);
Route::post('/verify-email', [VerifyEmailController::class, 'verifyEmail'])->middleware(['unverified','auth:sanctum']);
Route::post('/new-password', [ResetPasswordController::class, 'newPassword'])->middleware(['auth:sanctum']);
Route::post('/forgot-password', [OtpSenderController::class, 'otpResetSend'])->middleware(['verified']);
Route::post('/forgot-password/verify', [ResetPasswordController::class, 'verifyOtp'])->middleware(['verified']);
Route::post('/reset-password', [ResetPasswordController::class, 'resetPassword'])->middleware(['verified']);
Route::post('/register', [RegisteredUserController::class, 'store'])
    ->middleware('guest')
    ->name('register');

Route::post('/login', [AuthenticatedSessionController::class, 'store'])
    ->middleware('guest')
    ->name('login');

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth:sanctum')
    ->name('logout');
Route::get('/be-mitra',[userController::class,'changeRole'])->middleware(['auth:sanctum']);