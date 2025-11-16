<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\failReturn;
use App\Http\Resources\successReturn;
use App\Models\ResetToken;
use App\Models\User;
use App\Repositories\Abstract\OtpHandlerRepositoryInterface;
use App\Services\ResetPwMailer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;

class ResetPasswordController extends Controller
{
    private $otpHandler;
    private $resetPwMailer;

    public function __construct(OtpHandlerRepositoryInterface $otpHandler, ResetPwMailer $resetPwMailer)
    {
        $this->otpHandler = $otpHandler;
        $this->resetPwMailer = $resetPwMailer;
    }
    public function sendMailResetPw(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
        ]);
        $cacheResult = $this->createCache($request->email);
        if ($cacheResult) {
            return $cacheResult;
        }
        $email = $request->email;
        $token = $this->requestToken(User::where('email', $email)->first());
        $resetLink = env('FRONTEND_URL') . '/auth/reset-password#token=' . $token . '&email=' . $email ;
        $subject = 'Password Reset';
        return $this->resetPwMailer->sendResetPw($email, $resetLink, 'Reset Password', $subject);
    }
    public function checkToken(Request $request)
    {
        $request->validate([
            'token' => 'required|string',
            'email' => 'required|string|email',
        ]);
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return new failReturn([
                'status' => 404,
                'message' => 'User not found'
            ]);
        }
        $resetToken = ResetToken::where('user_id', $user->id)->firstOrFail();

        if (!$resetToken) {
            return new failReturn([
                'status' => 404,
                'message' => 'Token not found'
            ]);
        }

        if (Hash::check($request->token, $resetToken->token) === false) {
            return new failReturn([
                'status' => 400,
                'message' => 'Invalid token'
            ]);
        }
        if ($resetToken->expires_at < now()) {
            $resetToken->delete();
            return new failReturn([
                'status' => 400,
                'message' => 'Token expired'
            ]);
        }
        return new successReturn([
            'status' => 200,
            'message' => 'Token is valid'
        ]);
    }
    private function createCache($email)
    {
        $ip = request()->ip();
        $session = request()->session()->getId();
        // Buat cache key unik untuk throttle
        $key = 'otp_throttle:' . sha1($ip . '|' . $session);

        // Cek apakah throttle masih aktif
        if (Cache::has($key)) {
            return new failReturn([
                'status' => 429,
                'message' => 'Please wait before requesting another mail'
            ]);
        }
        Cache::put($key, true, now()->addSeconds(60));
        return false;
    }
    /**
     * POST: /api/reset-password
     * 
     * Reset the user's password using the provided token.
     * This method validates the token and updates the user's password.
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'password' => 'required|string|min:8',
            'password_confirmation' => 'required|string|same:password',
            'token' => 'required|string',
            'email' => 'required|string|email',
        ]);
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return new failReturn([
                'status' => 404,
                'message' => 'User not found'
            ]);
        }
        $resetToken = ResetToken::where('user_id', $user->id)->firstOrFail();

        if (!$resetToken) {
            return new failReturn([
                'status' => 404,
                'message' => 'Token not found'
            ]);
        }

        if (Hash::check($request->token, $resetToken->token) === false) {
            return new failReturn([
                'status' => 400,
                'message' => 'Invalid token'
            ]);
        }
        if ($resetToken->expires_at < now()) {
            $resetToken->delete();
            return new failReturn([
                'status' => 400,
                'message' => 'Token expired'
            ]);
        }
        if (Hash::check($request->token, $resetToken->token) === false) {
            return new failReturn([
                'status' => 400,
                'message' => 'Invalid token'
            ]);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        $resetToken->delete();
        $user->tokens()->delete();
        return new successReturn([
            'status' => 200,
            'message' => 'Password reset successfully'
        ]);
    }

    /**
     * POST: /api/new-password
     * 
     * Change the authenticated user's password.
     * This method allows the authenticated user to change their password by providing the old password and the new password.
     * @authenticated
     */
    public function newPassword(Request $request)
    {
        $request->validate([
            'old_password' => 'required|string',
            'password' => 'required|string|min:6',
            'password_confirmation' => 'required|string|same:password',
        ]);

        $user = auth()->user();
        if (!$user) {
            return new failReturn([
                'status' => 404,
                'message' => 'User not found'
            ]);
        }

        if (Hash::check($request->old_password, $user->password) === false) {
            return new failReturn([
                'status' => 400,
                'message' => 'Invalid old password'
            ]);
        }

        $user->password = Hash::make($request->password);
        $user->save();
        $user->tokens()->delete();
        return new successReturn([
            'status' => 200,
            'message' => 'Password reset successfully'
        ]);
    }

    protected function requestToken($user)
    {
        $existingToken = ResetToken::where('user_id', $user->id)->first();
        if ($existingToken) {
            $existingToken->delete();
            $token = bin2hex(random_bytes(16));
        }
        $token = bin2hex(random_bytes(16));
        $resetToken = new ResetToken();
        $resetToken->user_id = $user->id;
        $resetToken->token = Hash::make($token);
        $resetToken->expires_at = now()->addMinutes(5);
        $resetToken->save();
        return $token;
    }
}
