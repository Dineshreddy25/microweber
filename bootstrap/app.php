<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

$providers= [
    //  \MicroweberPackages\App\Providers\AppServiceProvider::class,
  //  \MicroweberPackages\App\Providers\AppServiceProvider::class,
];

//return Application::configure(basePath: dirname(__DIR__))
 return \MicroweberPackages\App\LaravelApplication::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withProviders($providers)
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
