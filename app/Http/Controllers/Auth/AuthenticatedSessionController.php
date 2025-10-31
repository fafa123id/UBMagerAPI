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
use Laravel\Passport\Token;

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
        $response = Http::asForm()->post(env('APP_URL') . '/oauth/token', [
            'grant_type' => 'password',
            'client_id' => env('PASSPORT_PASSWORD_GRANT_CLIENT_ID', 2),
            'client_secret' => env('PASSPORT_PASSWORD_GRANT_CLIENT_SECRET', 'aBc123xyz...'),
            'username' => $request->email,
            'password' => $request->password,
            'scope' => '*'
        ]);
        // 4. Kirim kembali respons token (berisi access_token dan refresh_token)
        return $response->json();
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
        auth()->user()->tokens()->each(function (Token $token) {
            $token->revoke();
            $token->refreshToken?->revoke();
        });

        return response()->json(['message' => 'Berhasil logout.']);
    }
}
