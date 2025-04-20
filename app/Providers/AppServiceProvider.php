<?php

namespace App\Providers;

use App\Observers\ProductObserver;
use App\Services\UserRoleId;
use App\Services\RouteInfo;
use App\Models\Product;
use App\Policies\ProductPolicy;
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
        $this->app->singleton(RouteInfo::class,function($app){
            return new RouteInfo();
        });
        $this->app->bind(
            \App\Repositories\Abstract\ProductRepositoryInterface::class,
            \App\Repositories\Concrete\ProductRepository::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Product::observe(ProductObserver::class);
        ResetPassword::createUrlUsing(function (object $notifiable, string $token) {
            return config('app.frontend_url')."/password-reset/$token?email={$notifiable->getEmailForPasswordReset()}";
        });
        Gate::policy(Product::class, ProductPolicy::class);
    }
}
