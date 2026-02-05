<?php

use App\Console\Commands\CheckSettlementSubmission;
use App\Http\Middleware\CheckActiveUser;
use App\Http\Middleware\EnsureUserIsActive;
use App\Http\Middleware\RedirectMobileDevice;
use App\Http\Middleware\RoleMiddleware;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withCommands([
        CheckSettlementSubmission::class
    ])
    ->withMiddleware(function ($middleware) {
        $middleware->alias([
            'role' => RoleMiddleware::class,
        ]);
        $middleware->append(CheckActiveUser::class);
        $middleware->append(EnsureUserIsActive::class);
        $middleware->append(RedirectMobileDevice::class);
    })
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // 🔐 Not authenticated (token missing / expired)
        $exceptions->render(function (
            AuthenticationException $e,
                                    $request
        ) {
            if ($request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Your session has expired. Please login again.',
                    'code' => 'SESSION_EXPIRED',
                ], 200); // 👈 friendly
            }
        });

        // 🚫 Access denied (role / permission)
        $exceptions->render(function (
            AccessDeniedHttpException $e,
                                      $request
        ) {
            if ($request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => 'You do not have permission to access this feature.',
                    'code' => 'ACCESS_DENIED',
                ], 200);
            }
        });
    })->create();
