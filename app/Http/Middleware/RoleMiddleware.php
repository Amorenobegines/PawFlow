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
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user || $user->role?->name !== 'Administrador') {
            abort(403, 'Solo el rol Administrador puede administrar usuarios.');
        } 

        if (! $user || $user->role?->name !== 'Recepcionista') {
            abort(403, 'Solo el rol Recepcionista puede acceder a esta sección.');
        }

        return $next($request);
    }
}
