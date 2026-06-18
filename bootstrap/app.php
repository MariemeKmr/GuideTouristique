<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Alias du middleware de controle de role.
        $middleware->alias([
            'role' => \App\Http\Middleware\RoleMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Un acces interdit (403) ne montre pas une page d'erreur :
        // on renvoie la personne vers son espace en l'informant, sans la deconnecter.
        $exceptions->render(function (HttpExceptionInterface $e, $request) {
            if ($e->getStatusCode() === 403 && ! $request->expectsJson()) {
                $message = $e->getMessage() ?: "Vous n'avez pas l'autorisation d'acceder a cette page.";

                if ($request->user()) {
                    return redirect()
                        ->route($request->user()->dashboardRoute())
                        ->with('error', $message);
                }

                return redirect()->route('login')->with('error', $message);
            }
        });
    })->create();
