<?php

namespace App\Providers;

use App\Observers\ProductObserver;
use App\Services\UserRoleId;
use App\Models\Product;
use App\Services\OtpMailer;
use Illuminate\Support\Facades\URL;
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
        $this->app->bind(
            \App\Repositories\Abstract\UserRepositoryInterface::class,
            \App\Repositories\Concrete\UserRepository::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }
        Product::observe(ProductObserver::class);
    }
}
