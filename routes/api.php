<?php

use App\Http\Controllers\Api\CheckoutController;
use App\Http\Controllers\Api\downloadController;
use Illuminate\Support\Facades\Route;

Route::get('payment/finish', [CheckoutController::class, 'finish']);
Route::get('/download/{files}', [downloadController::class,'download']);
require __DIR__ . '/Api/product.php';
require __DIR__ . '/Api/user.php';
require __DIR__ . '/auth.php';
require __DIR__ . '/Api/transaction.php';
require __DIR__ . '/Api/order.php';
require __DIR__ . '/Api/history.php';
require __DIR__ . '/Api/nego.php';
require __DIR__ . '/Api/rating.php';