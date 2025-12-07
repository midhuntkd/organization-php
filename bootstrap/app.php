<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Session\TokenMismatchException;

use Spatie\Permission\Middleware\RoleMiddleware;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Spatie\Permission\Middleware\RoleOrPermissionMiddleware;
use App\Http\Middleware\EnsureOrganizationPermission;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'org.context' => \App\Http\Middleware\EnsureOrgContext::class,
            'role' => RoleMiddleware::class,
            'permission' => PermissionMiddleware::class,
            'role_or_permission' => RoleOrPermissionMiddleware::class,
            'force.password.change' => \App\Http\Middleware\ForcePasswordChange::class,
            'auth' => \App\Http\Middleware\CustomAuthenticate::class,
            'org.permission' => EnsureOrganizationPermission::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (\Illuminate\Auth\AuthenticationException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthenticated.'], 401);
            }

            // ✅ Call our new public helper instead of protected redirectTo()
            $redirectUrl = app(\App\Http\Middleware\CustomAuthenticate::class)
                ->getRedirectUrl($request);

            return redirect()->guest($redirectUrl)
                ->with('error', 'Your session has expired. Please log in again.');
        });

        // Redirect CSRF/token mismatch (419) to the appropriate login page
        $exceptions->render(function (TokenMismatchException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Page expired. Please refresh and log in again.'], 419);
            }

            $redirectUrl = app(\App\Http\Middleware\CustomAuthenticate::class)
                ->getRedirectUrl($request);

            return redirect()->guest($redirectUrl)
                ->with('error', 'Your session has expired. Please log in again.');
        });
    })->create();
