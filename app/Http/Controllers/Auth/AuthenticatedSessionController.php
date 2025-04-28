<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

/**
 * @OA\Tag(
 *     name="Authentication",
 *     description="API Endpoints for user authentication"
 * )
 */
class AuthenticatedSessionController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/login",
     *     operationId="login",
     *     tags={"Authentication"},
     *     summary="Login user",
     *     description="Authenticates user and returns a token",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email", "password"},
     *             @OA\Property(property="email", type="string", format="email", example="user@example.com", description="User email"),
     *             @OA\Property(property="password", type="string", format="password", example="password123", description="User password")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Login successful",
     *         @OA\JsonContent(
     *             @OA\Property(property="token", type="string", example="1|laravel_sanctum_MQxyqMYMHVkH5OzJAMOFfgdv4nFhSltI4w65eUZH"),
     *             @OA\Property(property="message", type="string", example="Login Berhasil")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Invalid credentials"
     *     )
     * )
     */
    public function store(LoginRequest $request): JsonResponse
    {
        $request->authenticate();
        $user=Auth::user();
        $user->tokens()->delete();

        $data =
        [
            'token'=>$user->createToken("thetoken". $user->email)->plainTextToken,
            'message'=>'Login Berhasil'
        ];

        return response()->json($data,201);
    }

    /**
     * @OA\Post(
     *     path="/api/logout",
     *     operationId="logout",
     *     tags={"Authentication"},
     *     summary="Logout user",
     *     description="Destroys the user's current authenticated session and removes tokens",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=201,
     *         description="Logout successful",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Berhasil logout")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated"
     *     )
     * )
     */
    public function destroy(Request $request): JsonResponse
    {
        auth()->user()->tokens()->delete();

        return response()->json([
            'message'=>'Berhasil logout'
        ],201);
    }
}