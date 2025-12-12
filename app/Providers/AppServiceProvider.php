<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Passport;
use Modules\Auth\Repository\AuthRepository;
use Modules\Auth\Repository\AuthRepositoryInterface;
use Modules\Promotion\Repository\PromotionRepository;
use Modules\Promotion\Repository\PromotionRepositoryInterface;
use Modules\Shop\Repository\ShopRepository;
use Modules\Shop\Repository\ShopRepositoryInterface;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(AuthRepositoryInterface::class, AuthRepository::class);
        $this->app->bind(PromotionRepositoryInterface::class, PromotionRepository::class);
        $this->app->bind(ShopRepositoryInterface::class, ShopRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Schema::defaultStringLength(191);

        $token_expire_token_time = (int) env('SESSION_LIFETIME', '60');
        $token_expire_token_time = $token_expire_token_time ? $token_expire_token_time : 60;
        Passport::personalAccessTokensExpireIn(now()->addDays($token_expire_token_time));
        Gate::before(function ($user, $ability) {
            return $user->hasRole('SuperAdmin') ? true : null;
        });
    }
}
