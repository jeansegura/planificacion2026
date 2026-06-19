<?php

namespace App\Http\Middleware;

use App\Models\Role;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsurePermission
{
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        $user = $request->user();

        if (! $user) {
            abort(403);
        }

        if ((int) $user->id === 1 || ! Role::exists() || $user->hasPermission($permission)) {
            return $next($request);
        }

        abort(403, 'No tienes permiso para acceder a este modulo.');
    }
}
