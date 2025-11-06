<?php

namespace App\Http\Middleware;

use Closure;

class CookieTokenAuth
{
    public function handle($request, Closure $next)
    {
        $token = $request->cookie('auth_token'); // nama cookie kamu
        if ($token) {
            $request->headers->set('Authorization', 'Bearer ' . $token);
        }
        return $next($request);
    }
}
