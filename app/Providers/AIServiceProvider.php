<?php

namespace App\Providers;

use App\Services\AIServiceInterface;
use App\Services\MistralService;
use App\Services\QwenService;
use App\Services\OpenRouterService;
use HelgeSverre\Mistral\Mistral;
use Illuminate\Support\ServiceProvider;

class AIServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Регистрируем Mistral AI сервис
        $this->app->singleton(MistralService::class, function ($app) {
            return new MistralService(
                new Mistral(config('services.mistral.api_key'))
            );
        });

        // Регистрируем Qwen AI сервис
        $this->app->singleton(QwenService::class, function ($app) {
            return new QwenService(config('services.qwen.api_key'));
        });

        // Регистрируем Open Router сервис
        $this->app->singleton(OpenRouterService::class, function ($app) {
            return new OpenRouterService(config('services.openrouter.api_key'));
        });

        // Регистрируем AIServiceInterface с реализацией по умолчанию
        // По умолчанию используем OpenRouter, но можно изменить на другие
        $this->app->bind(AIServiceInterface::class, function ($app) {
            $defaultAIService = config('services.ai.default_service', 'openrouter');
            
            return match ($defaultAIService) {
                'mistral' => $app->make(MistralService::class),
                'qwen' => $app->make(QwenService::class),
                'openrouter' => $app->make(OpenRouterService::class),
                default => $app->make(OpenRouterService::class),
            };
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}