<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class GeminiServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Solo registros simples - NO usar View, Blade, etc aquí
        $this->app->singleton('gemini', function ($app) {
            return new \App\Services\GeminiService();
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Aquí puedes usar servicios que ya están cargados
        // Pero NO directamente en el método, usa callAfterResolving
        
        $this->callAfterResolving('blade.compiler', function ($blade) {
            // Aquí Blade ya está disponible y cargado
            $blade->directive('gemini', function ($expression) {
                return "<?php echo app('gemini')->process($expression); ?>";
            });
        });
    }
}