<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
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

    /**
     * @OA\Post(
     *     path="/api/forgot-password/verify",
     *     summary="Verify OTP sent to the user's email to reset password.",
     *     operationId="verifyOtp",
     *     tags={"Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email", "otp"},
     *             @OA\Property(property="email", type="string", example="user@example.com"),
     *             @OA\Property(property="otp", type="string", example="123456")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OTP verified and token generated.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="OTP verified"),
     *             @OA\Property(property="token", type="string", example="abcdef1234567890")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid OTP or expired OTP.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Invalid or OTP Expired")
     *         )
     *     )
     * )
     */
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
        return response()->json(['message' => 'OTP verified', 'token' => $token]);
    }

    /**
     * @OA\Post(
     *     path="/api/reset-password",
     *     summary="Reset user password using the reset token.",
     *     operationId="resetPassword",
     *     tags={"Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email", "password", "password_confirmation", "token"},
     *             @OA\Property(property="email", type="string", example="user@example.com"),
     *             @OA\Property(property="password", type="string", example="newPassword123"),
     *             @OA\Property(property="password_confirmation", type="string", example="newPassword123"),
     *             @OA\Property(property="token", type="string", example="abcdef1234567890")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Password successfully reset.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Password reset successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Token expired or invalid.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Token expired")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="User not found or token not found.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="User not found")
     *         )
     *     )
     * )
     */
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

        $resetToken = ResetToken::where('user_id', $user->id)->first();
        if (!$resetToken) {
            return response()->json(['message' => 'Token Not Found'], 404);
        }
        if ($resetToken->expires_at < now()) {
            $resetToken->delete();
            return response()->json(['message' => 'Token expired'], 400);
        }
        if (Hash::check($request->token, $resetToken->token) === false) {
            return response()->json(['message' => 'Invalid token'], 400);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        $resetToken->delete();
        $user->tokens()->delete();
        return response()->json(['message' => 'Password reset successfully']);
    }

    /**
     * @OA\Post(
     *     path="/api/new-password",
     *     summary="Allow authenticated user to change their own password.",
     *     operationId="newPassword",
     *  security={{"bearerAuth":{}}},
     *     tags={"Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"old_password", "password", "password_confirmation"},
     *             @OA\Property(property="old_password", type="string", example="oldPassword123"),
     *             @OA\Property(property="password", type="string", example="newPassword123"),
     *             @OA\Property(property="password_confirmation", type="string", example="newPassword123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Password successfully changed.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Password reset successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid old password or token.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Invalid token")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="User not found.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="User not found")
     *         )
     *     )
     * )
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
            return response()->json(['message' => 'User not found'], 404);
        }

        if (Hash::check($request->old_password, $user->password) === false) {
            return response()->json(['message' => 'Invalid token'], 400);
        }

        $user->password = Hash::make($request->password);
        $user->save();
        $user->tokens()->delete();
        return response()->json(['message' => 'Password reset successfully']);
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
