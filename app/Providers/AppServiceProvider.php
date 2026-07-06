<?php

/**
 * Proveedor de Laravel donde se registran configuraciones y servicios globales de la aplicacion.
 *
 * Mantiene documentada la responsabilidad de esta hoja de codigo dentro del MVC.
 */

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Registra servicios generales de la aplicacion.
     */
    public function register(): void
    {
        //
    }

    /**
     * Inicializa servicios generales de la aplicacion.
     */
    public function boot(): void
    {
        //
    }
}
