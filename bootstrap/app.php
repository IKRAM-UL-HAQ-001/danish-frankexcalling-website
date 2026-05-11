<?php

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
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->append(\App\Http\Middleware\SecurityHeadersMiddleware::class);
        
        // Register custom role-based route middleware aliases
        $middleware->alias([
            'admin'        => \App\Http\Middleware\AdminMiddleware::class,
            'assistant'    => \App\Http\Middleware\AssistantMiddleware::class,
            'exchange'     => \App\Http\Middleware\ExchangeUserMiddleware::class,
            'customercare' => \App\Http\Middleware\CustomerCareMiddleware::class,
            'csp'          => \App\Http\Middleware\ContentSecurityPolicy::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
