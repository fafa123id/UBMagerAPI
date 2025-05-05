<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUnverifiedUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! auth()->user()) {
            return response()->json(['message' => 'User not found.'], 404);
        }
        if ((auth()->user() instanceof MustVerifyEmail &&
            auth()->user()->hasVerifiedEmail())) {
            return response()->json(['message' => 'Your email address is verified.'], 409);
        }

        return $next($request);
    }
}
