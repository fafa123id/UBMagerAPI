<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\failReturn;
use App\Http\Resources\successReturn;
use App\Models\ResetToken;
use App\Models\User;
use App\Repositories\Abstract\OtpHandlerRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ResetPasswordController extends Controller
{
    private $otpHandler;

    public function __construct(OtpHandlerRepositoryInterface $otpHandler)
    {
        $this->otpHandler = $otpHandler;
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'otp' => 'required|string'
        ]);

        $otphandling = $this->otpHandler->verifyOtp($request->email, $request->otp);
        if ($otphandling === false) {
            return new failReturn([
                'status' => 400,
                'message' => 'Invalid or OTP Expired'
            ]);
        }
        $user = User::where('email', $request->email)->first();
        $token = $this->requestToken($user);
        if ($token === false) {
            return new failReturn([
                'status' => 500,
                'message' => 'failed to create token'
            ]);
        }
        return new successReturn([
            'status' => 200,
            'message' => 'OTP verified',
            'data' => [
                'token' => $token,
            ]
        ]);
    }


    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string|min:8',
            'password_confirmation' => 'required|string|same:password',
            'token' => 'required|string'
        ]);

        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return new failReturn([
                'status' => 404,
                'message' => 'User not found'
            ]);
        }

        $resetToken = ResetToken::where('user_id', $user->id)->first();
        if (!$resetToken) {
            return new failReturn([
                'status' => 404,
                'message' => 'Token not found'
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
