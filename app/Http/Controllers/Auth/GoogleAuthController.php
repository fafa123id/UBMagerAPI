<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\failReturn;
use App\Http\Resources\successReturn;
use App\Models\User;
use App\Repositories\Abstract\OtpHandlerRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Laravel\Socialite\Socialite;

class GoogleAuthController extends Controller
{
    protected $otp;
    public function __construct(OtpHandlerRepositoryInterface $otpHandlerRepositoryInterface)
    {
        $this->otp = $otpHandlerRepositoryInterface;
    }
    public function redirect()
    {
        return Socialite::driver('google')->stateless()->redirect();
    }
    public function callback()
    {
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();
        } catch (\Exception $e) {
            return redirect(env('FRONTEND_URL') . '/auth/login');
        }

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
            $cookie = cookie(
                'auth_token',
                $token,
                60 * 24 * 30,
                '/',                   // path
                env('SESSION_DOMAIN'),      // domain untuk subdomain sharing
                true,                  // secure
                true,                  // httpOnly
                false,                 // raw
                'lax'                 // SameSite ('None' jika FE & API beda origin)
            );
            return redirect(env('FRONTEND_URL') . '/auth/callback')->withCookie($cookie);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan saat meminta token.',
                'error'   => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }
    public function linkRedirect(Request $request)
    {

        $user = $request->user();
        $state = Crypt::encryptString($user->id);

        $url = Socialite::driver('google')->stateless()
            ->redirectUrl(env('GOOGLE_REDIRECT_URI_LINK'))
            ->with(['state' => $state])
            ->redirect()->getTargetUrl();
        return response()->json(['url' => $url]);
    }
    public function sendEmailForUnlinkGoogle(Request $request)
    {
        $user = auth()->user();
        if (!$user->email) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Set an email before unlinking Google account'
                ],
                400
            );
        }
        if (!$user->email_verified_at) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Verify your email before unlinking Google account'
                ],
                400
            );
        }
        if (!$user->password) {
            return new failReturn([
                'status' => 400,
                'message' => 'Set a password before unlinking Google account'
            ]);
        }
        if (!$user->google_id) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Google account not linked'
                ],
                400
            );
        }
        return $this->otp->sendOtp($user->email, rand(100000, 999999), 'Unlink Google', 'Unlink Google Account');
    }
    public function unlink(Request $request)
    {
        $user = $request->user();
        $request->validate([
            'otp' => 'required|string',
        ]);
        if (!$user->password && (!$user->email || !$user->username)) {
            return new failReturn([
                'status' => 400,
                'message' => 'Set an email/username and password before unlinking Google account'
            ]);
        }
        if (!$user->email_verified_at) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Verify your email before unlinking Google account'
                ],
                400
            );
        }
        if (!$user->google_id) {
            return new failReturn([
                'status' => 400,
                'message' => 'No Google account linked'
            ]);
        }
        $otpValid = $this->otp->verifyOtp($user->email, $request->otp);
        if (!$otpValid) {
            return new failReturn([
                'status' => 400,
                'message' => 'Invalid OTP'
            ]);
        }

        $user->update([
            'google_id' => null,
            'gmail' => null,
        ]);

        return new successReturn([
            'status' => 200,
            'message' => 'Google account unlinked successfully'
        ]);
    }
    public function linkCallback(Request $request)
    {

        $settingsUrl = env('FRONTEND_URL') . '/profile';

        if ($request->has('error')) {
            return redirect($settingsUrl);
        }

        if (!$request->has('state')) {
            return redirect($settingsUrl . '?error=Invalid%20request');
        }

        try {
            $userId = Crypt::decryptString($request->input('state'));
            $user = User::find($userId);

            if (!$user) {
                return redirect($settingsUrl . '?error=User%20not%20found');
            }

            // 2. Dapetin data user dari Google
            $googleUser = Socialite::driver('google')->stateless()
                ->redirectUrl(env('GOOGLE_REDIRECT_URI_LINK'))
                ->user();

            // 3. LOGIKA UTAMA: Cek Google ID ini udah dipake orang lain belom
            $existingLink = User::where('google_id', $googleUser->id)
                ->where('id', '!=', $user->id) // <- Punya user lain
                ->exists();

            if ($existingLink) {
                return redirect($settingsUrl . '?error=Google%20account%20already%20linked');
            }

            $user->update([
                'google_id' => $googleUser->id,
                'gmail' => $googleUser->email,
            ]);

            return redirect($settingsUrl . '?success=Google%20account%20link%20complete&&need_refresh=true');
        } catch (\Exception $e) {
            // Tangkap error (misal: state invalid, decrypt gagal)
            report($e);
            return redirect($settingsUrl . '?error=Google%20linking%20failed');
        }
    }
}
