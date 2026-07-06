<?php

/**
 * Middleware de validacion de permisos; decide si la solicitud puede continuar segun el usuario autenticado.
 *
 * Mantiene documentada la responsabilidad de esta hoja de codigo dentro del MVC.
 */

namespace App\Http\Middleware;

use App\Models\Role;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsurePermission
{
    // Permite o bloquea la solicitud segun uno o varios permisos del usuario.
    public function handle(Request $request, Closure $next, string ...$permissions): Response
    {
        $user = $request->user();

        if (! $user) {
            abort(403);
        }

        // El primer usuario conserva acceso total para recuperacion administrativa.
        // Si todavia no existen roles, Laravel permite continuar durante instalacion.
        if ((int) $user->id === 1 || ! Role::exists()) {
            return $next($request);
        }

        // La ruta puede declarar varias opciones, por ejemplo:
        // permission:plans.validate,plans.approve.
        foreach ($permissions as $permission) {
            if ($user->hasPermission($permission)) {
                return $next($request);
            }
        }

        abort(403, 'No tienes permiso para acceder a este modulo.');
    }
}
