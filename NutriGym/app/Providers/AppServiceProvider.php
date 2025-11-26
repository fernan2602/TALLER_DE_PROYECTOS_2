<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB; 
use Illuminate\Support\Facades\Auth;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton('gemini', function ($app) {
            return new \App\Services\GeminiService();
        });
    }

    public function boot()
    {
        // Compartir el contador de objetivos y preferencias con todas las vistas
        View::composer('*', function ($view) {
            if (Auth::check()) {
                $totalObjetivosUsuario = DB::table('asignacion_objetivo')
                    ->where('id_usuario', Auth::id())
                    ->count();
                
                $totalPreferenciasUsuario = DB::table('asignacion_preferencia')
                    ->where('id_usuario', Auth::id())
                    ->count();

                $totalMenusUsuario = DB::table('asignacion_menus')
                    ->where('id_usuario', Auth::id())
                    ->count();

                


            } else {
                $totalObjetivosUsuario = 0;
                $totalPreferenciasUsuario = 0;
                $totalMenusUsuario = 0; 
            }
            
            // Pasar ambas variables en un solo with (más eficiente)
            $view->with([
                'totalObjetivosUsuario' => $totalObjetivosUsuario,
                'totalPreferenciasUsuario' => $totalPreferenciasUsuario,
                'totalMenusUsuario' => $totalMenusUsuario
            ]);
        });
    }
}