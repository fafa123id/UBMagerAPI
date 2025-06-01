<?php

use App\Http\Controllers\Api\CheckoutController;


Route::get('payment/finish', [CheckoutController::class, 'finish']);
require __DIR__ . '/Api/product.php';
require __DIR__ . '/Api/user.php';
require __DIR__ . '/auth.php';
require __DIR__ . '/Api/transaction.php';
require __DIR__ . '/Api/order.php';
require __DIR__ . '/Api/history.php';
require __DIR__ . '/Api/nego.php';
require __DIR__ . '/Api/rating.php';