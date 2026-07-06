<?php

/**
 * Define comandos de consola propios de Laravel para tareas internas del proyecto.
 *
 * Mantiene documentada la responsabilidad de esta hoja de codigo dentro del MVC.
 */
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');
