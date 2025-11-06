<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
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
        $request->validate([
            'emailor_username' => 'required|string',
            'password' => 'required|string',
        ]);
        $email = User::where('email', $request->emailor_username)->orWhere('username', $request->emailor_username)->firstOrFail()->email;
        $credentials = [
            'email' => $email,
            'password' => $request->password,
        ];
        if (!auth()->attempt($credentials)) {
            return response()->json(['message' => 'Email atau password salah.'], 401);
        }

        try {
            // Pakai url() agar tidak tergantung APP_URL di container
            $tokenResp = Http::asForm()->post(url('/oauth/token'), [
                'grant_type' => 'password',
                'client_id' => config('services.passport.password_client_id'),
                'client_secret' => config('services.passport.password_client_secret'),
                'username' => $email,
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
            $response_token = $tokenResp->json();
            $refreshToken = $response_token['refresh_token'];
            $token = $response_token['access_token'];
            $refreshTokenCookie = cookie(
                'refresh_token',
                $refreshToken,
                60 * 24 * 30,
                '/',
                null,
                config('session.secure'),
                true,
                false,
                'lax'
            );
            $authTokenCookie = cookie(
                'auth_token',
                $token,
                60 * 24 * 30,
                '/',
                null,
                config('session.secure'),
                true,
                false,
                'lax'
            );
            // Sukses: bungkus ke JsonResponse
            return response()->json([
                'token_type' => $response_token['token_type'],
                'expires_in' => $response_token['expires_in'],
            ], $tokenResp->status())->withCookie($refreshTokenCookie)->withCookie($authTokenCookie);
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
    public function destroy(): JsonResponse
    {
        // Otomatis tahu user dari token yang dipakai
        auth()->user()->tokens()->each(function (Token $token) {
            $token->revoke();
            $token->refreshToken?->revoke();
        });
        $cookie = Cookie::forget('refresh_token');
        $authCookie = Cookie::forget('auth_token');

        return response()->json(['message' => 'Berhasil logout.'])->withCookie($cookie, $authCookie);
    }
    public function refresh(Request $request): JsonResponse
    {
        try {
            $refreshToken = $request->cookie('refresh_token');
            $tokenResp = Http::asForm()->post(url('/oauth/token'), [
                'grant_type' => 'refresh_token',
                'client_id' => config('services.passport.password_client_id'),
                'client_secret' => config('services.passport.password_client_secret'),
                'refresh_token' => $refreshToken,
                'scope' => '', // atau '*' jika memang perlu
            ]);

            if ($tokenResp->failed()) {
                return response()->json(
                    $tokenResp->json() ?? ['message' => 'Gagal mendapatkan token.'],
                    $tokenResp->status()
                );
            }
            $response_token = $tokenResp->json();
            $refreshToken = $response_token['refresh_token'];
            $token = $response_token['access_token'];
            $refreshTokenCookie = cookie(
                'refresh_token',
                $refreshToken,
                60 * 24 * 30,
                '/',
                null,
                config('session.secure'),
                true,
                false,
                'lax'
            );
            $authTokenCookie = cookie(
                'auth_token',
                $token,
                60 * 24 * 30,
                '/',
                null,
                config('session.secure'),
                true,
                false,
                'lax'
            );
            return response()->json([
                'access_token' => $token,
                'token_type' => $response_token['token_type'],
                'expires_in' => $response_token['expires_in'],
            ], $tokenResp->status())->withCookie($refreshTokenCookie)->withCookie($authTokenCookie);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan saat meminta token.',
                'error'   => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }
}
