<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Laravel\Socialite\Socialite;

class GoogleAuthController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }
    public function callback()
    {
        $googleUser = Socialite::driver('google')->user();
        $password = Hash::make(Str::random(24));
        $user = User::updateOrCreate(
            [
                'google_id' => $googleUser->id,
            ],
            [
                'name' => $googleUser->name,
                'username' => preg_replace('/\s+/', '', strtolower($googleUser->name)) . rand(1000, 9999),
                'email' => $googleUser->email,
                'password' => $password
            ]
        );
         try {
            $tokenResp = Http::asForm()->post(url('/oauth/token'), [
                'grant_type' => 'password',
                'client_id' => config('services.passport.password_client_id'),
                'client_secret' => config('services.passport.password_client_secret'),
                'username' => $googleUser->email,
                'password' => $password,
                'scope' => '',
            ]);

            if ($tokenResp->failed()) {
                return response()->json(
                    $tokenResp->json() ?? ['message' => 'Gagal mendapatkan token.'],
                    $tokenResp->status()
                );
            }

            $accessToken = $tokenResp->json()['access_token'];
            $refreshToken = $tokenResp->json()['refresh_token'];
            return redirect(env('FRONTEND_URL') . '/auth/callback?token=' . $accessToken .'&refresh='. $refreshToken);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan saat meminta token.',
                'error'   => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }
}
