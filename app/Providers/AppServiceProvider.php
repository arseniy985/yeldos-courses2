<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Сервисы для AI теперь регистрируются в AIServiceProvider
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
