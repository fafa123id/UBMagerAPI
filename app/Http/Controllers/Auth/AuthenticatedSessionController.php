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

        // 1. Cek kredensial manual
        $user = User::where('email', $credentials['email'])->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return response()->json(['message' => 'Email atau password salah.'], 401);
        }

        // 2. Kredensial valid, minta token ke Passport
        // Kita "menembak" request ke endpoint /oauth/token internal
        $response = Http::asForm()->post(url('/oauth/token'), [
            'grant_type' => 'password',
            'client_id' => env('PASSPORT_PASSWORD_GRANT_CLIENT_ID'),
            'client_secret' => env('PASSPORT_PASSWORD_GRANT_CLIENT_SECRET'),
            'username' => $request->email,
            'password' => $request->password,
            'scope' => '*',
        ]);

        // 3. Cek jika request token gagal
        if ($response->failed()) {
            return response()->json(['message' => 'Gagal mendapatkan token.'], $response->status());
        }

        // 4. Kirim kembali respons token (berisi access_token dan refresh_token)
        return response()->json($response->json());
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
