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

        try {
            // Pakai url() agar tidak tergantung APP_URL di container
            $tokenResp = Http::asForm()->post(url('/oauth/token'), [
                'grant_type' => 'password',
                'client_id' => env('PASSPORT_PASSWORD_GRANT_CLIENT_ID'),
                'client_secret' => env('PASSPORT_PASSWORD_GRANT_CLIENT_SECRET'),
                'username' => $request->email,
                'password' => $request->password,
                'scope' => '', // atau '*' jika memang perlu
            ]);

            // Jika Passport memberi error (4xx/5xx), teruskan status & body-nya
            if ($tokenResp->failed()) {
                // Bisa berisi error_description dari Passport
                return response()->json(
                    $tokenResp->json() ?? ['message' => 'Gagal mendapatkan token.'],
                    $tokenResp->status()
                );
            }

            // Sukses: bungkus ke JsonResponse
            return response()->json($tokenResp->json(), $tokenResp->status());
        } catch (\Throwable $e) {
            // Antisipasi network/exception lain
            return response()->json([
                'message' => 'Terjadi kesalahan saat meminta token.',
                'error'   => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
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
