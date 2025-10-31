<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;

class AuthenticatedSessionController extends Controller
{

    /**
     * POST: /api/login
     * 
     * Login a user and return a token.
     * This method authenticates the user and generates a token for API access.
     */
    public function store(Request $request): JsonResponse
    {
        $credentials = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if (!auth()->attempt($credentials)) {
            return response()->json(['message' => 'Email atau password salah.'], 401);
        }
        $user = auth()->user();
        $token = $user->createToken('UserToken')->accessToken;
        // 4. Kirim kembali respons token (berisi access_token dan refresh_token)
        return response()->json(['access_token' => $token]);
    }


    /**
     * POST: /api/logout
     * Revoke token yang sedang dipakai.
     */


    /**
     * GET: /api/user
     * Mendapatkan data user yang sedang login.
     */

    /**
     * POST: /api/logout
     * 
     * Logout the authenticated user and delete their token.
     * This method revokes the user's token, effectively logging them out.
     * @authenticated
     */
    public function destroy(Request $request): JsonResponse
    {
        // Otomatis tahu user dari token yang dipakai
        $request->user()->token()->revoke();

        return response()->json(['message' => 'Berhasil logout.']);
    }
}
