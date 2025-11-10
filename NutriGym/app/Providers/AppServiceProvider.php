<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton('gemini', function ($app) {
            return new \App\Services\GeminiService();
        });
    }

    public function boot(): void
    {
        // Tu lógica de Gemini aquí
    }
}