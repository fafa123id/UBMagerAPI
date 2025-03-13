<?php
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('index');
});
Route::get('/api-routes', function () {
    $routes = collect(Route::getRoutes())->map(function ($route) {
        return [
            'method' => implode('|', $route->methods()),
            'uri' => $route->uri(),
        ];
    })->filter(function ($route) {
        return str_starts_with($route['uri'], 'api/');
    })->values();

    return response()->json($routes);
});

require __DIR__.'/auth.php';