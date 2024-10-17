<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle($request, Closure $next, ...$roles)
    {
        $user = Auth::user();

        if (!in_array($user->role, $roles)) {
            return response()->json(['error' => 'No tienes permiso para acceder a esta página.'], 403);
        }

        return $next($request);
    }
}
