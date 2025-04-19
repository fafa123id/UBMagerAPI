<?php

namespace App\Http\Middleware;

use App\Services\UserRoleId;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        $usr = Auth::guard('sanctum')->user();
    
        if ($this->userRoleId->getRole($usr)=== 1) {
            return $next($request);
        }
        
       

        return response()->json(['message' => 'Access denied'], 401);
    }
}
