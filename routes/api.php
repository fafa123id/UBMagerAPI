<?php

Route::get('payment/finish', function () {
    return view('midtrans.finish');
});
require __DIR__.'/Api/product.php';
require __DIR__.'/Api/user.php';
require __DIR__.'/auth.php';
require __DIR__.'/Api/transaction.php';
require __DIR__.'/Api/order.php';