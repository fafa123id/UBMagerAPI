<?php

namespace App\Http\Middleware;

use App\Services\UserRoleId;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Seller
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    protected $userRoleId;

    public function __construct(UserRoleId $usr)
    {
        $this->userRoleId = $usr;
    }

    public function handle(Request $request, Closure $next): Response
    {
        if ($this->userRoleId->getRole($request->user) === 1) {
            return $next($request);
        }

        return response()->json(['message' => 'Access denied'], 401);
    }
}
