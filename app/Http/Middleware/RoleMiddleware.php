<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use App\Models\User; 

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = $request->user();
       // Verificar si el usuario está autenticado y tiene un rol válido
       if (! $user || ! in_array($user->role?->name, $roles)) {
           abort(403, 'No tienes permiso para acceder a esta página.');
       }
        return $next($request);
    }
}
