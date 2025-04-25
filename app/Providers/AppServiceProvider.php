<?php

namespace App\Providers;

use App\Models\otp;
use App\Observers\ProductObserver;
use App\Services\UserRoleId;
use App\Services\RouteInfo;
use App\Models\Product;
use App\Policies\OtpPolicy;
use App\Policies\ProductPolicy;
use App\Services\OtpMailer;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(UserRoleId::class,function($app){
            return new UserRoleId();
        });
        $this->app->singleton(OtpMailer::class,function($app){
            return new OtpMailer();
        });
        $this->app->bind(
            \App\Repositories\Abstract\OtpHandlerRepositoryInterface::class,
            \App\Repositories\Concrete\OtpHandlerRepository::class
        );
        $this->app->bind(
            \App\Repositories\Abstract\ProductRepositoryInterface::class,
            \App\Repositories\Concrete\ProductRepository::class
        );
        $this->app->bind(
            \App\Repositories\Abstract\CartRepositoryInterface::class,
            \App\Repositories\Concrete\CartRepository::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Product::observe(ProductObserver::class);
    }
}
