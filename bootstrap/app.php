<?php

use App\Http\Middleware\RoleMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->trustProxies(at: '*');

        $middleware->statefulApi();

        $middleware->alias([
            'role' => RoleMiddleware::class,
        ]);

        $middleware->redirectTo(
            guests: '/login',
            users: function ($request) {
                $user = $request->user();
                if ($user) {
                    $roleValue = is_object($user->role) ? $user->role->value : $user->role;
                    return $roleValue === 'admin' ? '/admin/dashboard' : '/dashboard';
                }
                return '/dashboard';
            }
        );
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })
    ->create();