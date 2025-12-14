<?php

use App\Http\Controllers\Api\CheckoutController;
use App\Http\Controllers\Api\downloadController;
use Illuminate\Support\Facades\Route;

Route::get('payment/finish', [CheckoutController::class, 'finish']);
Route::get('/download/{files}', [downloadController::class,'download']);
Route::get('/check-auth', function () {
    return response()->json(['message' => 'Authenticated'], 200);
})->middleware('auth:api');
Route::middleware('web')->get('/debug-session', function (Request $request) {
    $count = session('debug_count', 0);
    $count++;
    session(['debug_count' => $count]);

    return response()->json([
        'session_id'  => session()->getId(),
        'debug_count' => $count,
    ]);
});

require __DIR__ . '/Api/product.php';
require __DIR__ . '/Api/user.php';
require __DIR__ . '/auth.php';
require __DIR__ . '/Api/transaction.php';
require __DIR__ . '/Api/order.php';
require __DIR__ . '/Api/history.php';
require __DIR__ . '/Api/nego.php';
require __DIR__ . '/Api/rating.php';
require __DIR__ . '/Api/favorites.php';
require __DIR__ . '/Api/analytics.php';