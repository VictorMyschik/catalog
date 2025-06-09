<?php

use App\Http\Middleware\HandleVerified;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
        then: function () {
            Route::middleware('api')
                ->prefix('api/v1')
                ->name('api.v1.')
                ->group(base_path('routes/api/v1.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'api-verified' => HandleVerified::class,
        ]);

    })
    ->withCommands([
        __DIR__ . '/../app/Console/Commands',
    ])
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (Throwable $e, $request) {
            if ($request->expectsJson()) {
                if ($e instanceof AuthenticationException) {
                    return new JsonResponse([
                        'status'  => 'error',
                        'content' => $e->getMessage(),
                    ], 401);
                }

                if ($e instanceof ValidationException) {
                    return new JsonResponse([
                        'status'  => 'error',
                        'content' => [
                            'message' => 'Ошибка валидации',
                            'errors'  => $e->validator->errors(),
                        ],
                    ], 422);
                }

                if ($e instanceof AccessDeniedHttpException) {
                    return new JsonResponse([
                        'status'  => 'error',
                        'content' => [
                            'message' => $e->getMessage(),
                        ],
                    ], 403);
                }

                if ($e instanceof NotFoundHttpException) {
                    return new JsonResponse([
                        'status'  => 'error',
                        'content' => [
                            'message' => $e->getMessage(),
                        ],
                    ], 404);
                }

                if ($e instanceof LogicException) {
                    return new JsonResponse([
                        'status'  => 'error',
                        'content' => [
                            'message' => $e->getMessage(),
                        ],
                    ], 422);
                }
            }
        });
    })->create();
