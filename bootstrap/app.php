<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        
        $middleware->alias([
            'super_admin' => \App\Http\Middleware\Super_admin::class,
            'admin' => \App\Http\Middleware\Admin::class,
            'user' => \App\Http\Middleware\User::class,
            'checkRole' => \App\Http\Middleware\CheckRole::class,
            'checkEventCreator' => \App\Http\Middleware\CheckEventCreator::class,
            'checkFormOwner' => \App\Http\Middleware\CheckFormOwner::class,
        ]);

    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
