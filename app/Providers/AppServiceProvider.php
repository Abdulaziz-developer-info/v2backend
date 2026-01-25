<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
public function boot(): void
    {
        Paginator::useBootstrapFive();

        // Sanctumga qaysi model uchun tokenlarni tekshirishni ko'rsatish
        // (Agar auth.php da hammasini to'g'ri qilgan bo'lsangiz, bu ixtiyoriy)
        \Laravel\Sanctum\Sanctum::usePersonalAccessTokenModel(\Laravel\Sanctum\PersonalAccessToken::class);
    }
}
