<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Sanctum\Sanctum;

class SanctumServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        // Sanctum ni to'g'ri sozlash - tokenlarni hashlab saqlash
        Sanctum::authenticateAccessTokensUsing(
            function ($token, $isValid) {
                // Token validation logic
                return $token && $isValid;
            }
        );
    }
}
