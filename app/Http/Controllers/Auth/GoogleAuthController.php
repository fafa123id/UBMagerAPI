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
        return Socialite::driver('google')->stateless()->redirect();
    }
    public function callback()
    {
        $googleUser = Socialite::driver('google')->stateless()->user();

        $user = User::where('gmail', $googleUser->email)->first();
        if (!$user) {
            $user = User::create(

                [
                    'name' => $googleUser->name,
                    'username' => preg_replace('/\s+/', '', strtolower($googleUser->name)) . rand(1000, 9999),
                    'gmail' => $googleUser->email,
                    'google_id' => $googleUser->id,
                ]
            );
        } else {
            $user->update([
                'google_id' => $googleUser->id,
            ]);
        }

        try {
            $tokenResp = $user->createToken('google-auth-token');
            $token = $tokenResp->accessToken;

            return redirect(env('FRONTEND_URL') . '/auth/callback?token=' . $token);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan saat meminta token.',
                'error'   => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }
}
