<?php
use App\Services\RouteInfo;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'info'], function () {
    $apiInfoService = app(RouteInfo::class);
    $routes = $apiInfoService->getInfo();
    foreach ($routes as $route) {
        Route::get($route['uri'], function () use ($route) {
            return view('inforoute.index', [
                'route' => $route['uri'],
                'description' => $route['description'],
            ]);
        });
    }

});

