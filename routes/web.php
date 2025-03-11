<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json(['Service UBMagerAPI Sedang Berjalan, By UBMager'],201);
});

