<?php

// $cacheDir = __DIR__.'/cache';
// if (!is_dir($cacheDir)) {
//     @mkdir($cacheDir, 0755, true);
// }
// @chmod($cacheDir, 0755);

use App\Http\Middleware\EnsurePinIsSet;
use App\Http\Middleware\RedirectIfAuthenticated;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;


return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //Manually register RedirectIfAuthenticated Middleware
         $middleware->alias([
            'redirect.if.authenticated' => RedirectIfAuthenticated::class,
            'pin.required' => EnsurePinIsSet::class,
        ]);


    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
