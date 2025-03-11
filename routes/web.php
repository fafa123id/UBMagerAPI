<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json(['Service UBMagerAPI Sedang Berjalan, By UBMager'],201);
});

require __DIR__.'/auth.php';