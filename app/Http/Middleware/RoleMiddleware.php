<?php

namespace App\Http\Middleware;

use App\Enums\Role;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(
        Request $request,
        Closure $next,
        string ...$allowedRoles
    ): Response {
        $user = $request->user();

        if (! $user) {
            abort(403);
        }

        $currentRole = $user->role instanceof Role
            ? $user->role->value
            : (string) $user->role;

        abort_unless(
            in_array($currentRole, $allowedRoles, true),
            403
        );

        return $next($request);
    }
}