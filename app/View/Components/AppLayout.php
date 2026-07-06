<?php

/**
 * Componente de vista para layout principal autenticado; reutiliza estructura visual en pantallas Blade.
 *
 * Mantiene documentada la responsabilidad de esta hoja de codigo dentro del MVC.
 */

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class AppLayout extends Component
{
    /**
     * Devuelve la vista que representa este componente.
     */
    public function render(): View
    {
        return view('layouts.app');
    }
}
