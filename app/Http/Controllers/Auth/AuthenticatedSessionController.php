<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class AuthenticatedSessionController extends Controller
{

    /**
     * POST: /api/login
     * Login a user and return a token.
     * This method authenticates the user and generates a token for API access.
     */
    public function store(LoginRequest $request): JsonResponse
    {
        $request->authenticate();
        $user = Auth::user();
        $user->tokens()->delete();

        $data =
            [
                'token' => $user->createToken("thetoken" . $user->email)->plainTextToken,
                'message' => 'Login Berhasil'
            ];

        return response()->json($data, 201);
    }
    /**
     * POST: /api/logout
     * Logout the authenticated user and delete their token.
     * This method revokes the user's token, effectively logging them out.
     * @authenticated
     */
    public function destroy(Request $request): JsonResponse
    {
        auth()->user()->tokens()->delete();

        return response()->json([
            'message' => 'Berhasil logout'
        ], 201);
    }
}