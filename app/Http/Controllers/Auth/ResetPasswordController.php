<?php

namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;
use App\Models\ResetToken;
use App\Models\User;
use App\Repositories\Abstract\OtpHandlerRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;


/**
 * Send OTP to the user's phone number.
 *
 * @param Request $request
 * @return \Illuminate\Http\JsonResponse
 */
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
            return response()->json(['message' => 'Invalid or OTP Expired'], 400);
        }
        $user = User::where('email', $request->email)->first();
        $token = $this->requestToken($user);
        if ($token === false) {
            return response()->json(['message' => 'Failed to generate token'], 500);
        }
        return response()->json(['message' => 'OTP verified'
        , 'token' => $token]);
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
            return response()->json(['message' => 'User not found'], 404);
        }

        $resetToken = ResetToken::where('user_id', $user->id)
            ->first();
        if (!$resetToken) {
            return response()->json(['message' => 'Token Not Found'], 404);
        }
        if ($resetToken->expires_at < now()) {
            $resetToken->delete(); // Delete the token after use
            return response()->json(['message' => 'Token expired'], 400);
        }
        if (Hash::check($request->token,$resetToken->token) === false) {
            return response()->json(['message' => 'Invalid token'], 400);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        $resetToken->delete(); // Delete the token after use
        $user->tokens()->delete(); // Delete all tokens for the user
        return response()->json(['message' => 'Password reset successfully']);
    }
    public function newPassword(Request $request)
    {
        $request->validate([
            'old_password' => 'required|string',
            'password' => 'required|string|min:6',
            'password_confirmation' => 'required|string|same:password',
        ]);

        $user = auth()->user();
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        if (Hash::check($request->old_password,$user->password) === false) {
            return response()->json(['message' => 'Invalid token'], 400);
        }

        $user->password = Hash::make($request->password);
        $user->save();
        $user->tokens()->delete(); // Delete all tokens for the user
        return response()->json(['message' => 'Password reset successfully']);
    }
    protected function requestToken($user){
        $existingToken = ResetToken::where('user_id', $user->id)->first();
        if ($existingToken) {
            $existingToken->delete(); 
            $token = bin2hex(random_bytes(16));
        }
        $token=bin2hex(random_bytes(16));
        // Save the token to the database
        $resetToken = new ResetToken();
        $resetToken->user_id = $user->id;
        $resetToken->token = Hash::make($token);
        $resetToken->expires_at = now()->addMinutes(5); 
        $resetToken->save();
        return $token;
    }
}

