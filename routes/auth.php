<?php

use App\Http\Controllers\Api\userController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\GoogleAuthController;
use App\Http\Controllers\Auth\OtpSenderController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;

Route::post('/verify-email/send', [OtpSenderController::class, 'otpVerifySend'])->middleware(['unverified','auth:api','web']);
Route::post('/verify-email', [VerifyEmailController::class, 'verifyEmail'])->middleware(['unverified','auth:api']);
Route::post('/new-password', [ResetPasswordController::class, 'newPassword'])->middleware(['auth:api']);
Route::post('/new-email', [userController::class, 'newEmail'])->middleware(['auth:api']);
Route::post('/change-email/send', [userController::class, 'sendChangeEmailOtp'])->middleware(['auth:api','web']);
Route::post('/change-email', [userController::class, 'changeEmail'])->middleware(['auth:api']);
Route::post('/forgot-password', [ResetPasswordController::class, 'sendMailResetPw'])->middleware(['verified','web']);
Route::post('/forgot-password/token', [ResetPasswordController::class, 'checkToken'])->middleware(['verified']);
Route::post('/reset-password', [ResetPasswordController::class, 'resetPassword'])->middleware(['verified']);
Route::get('/reset-password', [ResetPasswordController::class, 'handleRedirect'])->middleware(['verified']);
Route::post('/register', [RegisteredUserController::class, 'store'])
    ->name('register');

Route::post('/login', [AuthenticatedSessionController::class, 'store'])
    ->name('login');
Route::post('/set-password', [userController::class, 'addPassword'])
    ->middleware('auth:api')
    ->name('set-password');
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth:api')
    ->name('logout');
Route::post('/refresh ', [AuthenticatedSessionController::class, 'refresh'])
    ->name('refresh');
Route::get('/me', function () {
    return response(true,200);
})->middleware(['auth:api']);
Route::get('/be-mitra',[userController::class,'changeRole'])->middleware(['auth:api']);

Route::get('/auth/google/redirect', [GoogleAuthController::class, 'redirect']);
Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback']);

Route::get('/auth/google/link/callback', [GoogleAuthController::class, 'linkCallback']);
Route::get('/auth/google/link/redirect', [GoogleAuthController::class, 'linkRedirect'])->middleware('auth:api');
Route::post('/auth/google/unlink', [GoogleAuthController::class, 'unlink'])->middleware('auth:api');
Route::post('/auth/google/send-unlink-email', [GoogleAuthController::class, 'sendEmailForUnlinkGoogle'])->middleware(['auth:api', 'web']);