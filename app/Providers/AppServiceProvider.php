<?php

namespace App\Providers;

use App\Services\MistralService;
use HelgeSverre\Mistral\Mistral;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(Mistral::class, function () {
            return new Mistral(apiKey: config('mistral.api_key'));
        });
        $this->app->singleton(MistralService::class, MistralService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
