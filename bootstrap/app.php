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
        // Behind a TLS-terminating proxy the app only learns the original
        // scheme from X-Forwarded-*, which Laravel ignores until the proxy is
        // trusted. Set TRUSTED_PROXIES to '*' when the container is only
        // reachable through that proxy, or to a comma-separated IP list.
        $proxies = env('TRUSTED_PROXIES');

        if (filled($proxies)) {
            $middleware->trustProxies(
                at: $proxies === '*' ? '*' : explode(',', $proxies),
            );
        }
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
