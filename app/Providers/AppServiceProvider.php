<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Passport;
use Modules\Auth\Repository\Authrepository;
use Modules\Auth\Repository\AuthrepositoryInterface;
use Modules\Promotion\Repository\PromotionRepository;
use Modules\Promotion\Repository\PromotionRepositoryInterface;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(AuthrepositoryInterface::class, Authrepository::class);
        $this->app->bind(PromotionRepositoryInterface::class, PromotionRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $token_expire_token_time = (int) env('SESSION_LIFETIME', '60');
        $token_expire_token_time = $token_expire_token_time ? $token_expire_token_time : 60;
        Passport::personalAccessTokensExpireIn(now()->addDays($token_expire_token_time));
    }
}
