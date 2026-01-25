<?php

use App\Http\Middleware\AdminAuth;
use App\Http\Middleware\ApiTokenAuth;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Auth\AuthenticationException; // Buni qo'shish shart
use Illuminate\Http\Request;                // Buni qo'shish shart

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {

        // ❌ API uchun MUAMMO keltirayotgan middleware ni olib tashlaymiz
        $middleware->remove(\Illuminate\Http\Middleware\ValidatePathEncoding::class);

        // CORS qolsin
        $middleware->append(\Illuminate\Http\Middleware\HandleCors::class);

        // ❌ API uchun redirect UMUMAN BO‘LMASIN
        // $middleware->redirectGuestsTo(function (Request $request) {
        //     return $request->is('api/*') ? null : route('admin.login');
        // });

        $middleware->alias([
            'admin.auth' => AdminAuth::class,
            'api.auth' => ApiTokenAuth::class,
        ]);
    })

    ->withExceptions(function (Exceptions $exceptions): void {
        // API uchun Unauthenticated xatosini JSON ko'rinishida qaytarish
        $exceptions->render(function (AuthenticationException $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'status' => false,
                    'message' => 'Unauthenticated or Invalid Token.',
                ], 401);
            }
        });
    })->create();