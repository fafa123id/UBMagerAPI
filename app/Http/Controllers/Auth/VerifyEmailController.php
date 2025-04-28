<?php

namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Repositories\Abstract\OtpHandlerRepositoryInterface;
use Illuminate\Http\Request;


/**
 * Send OTP to the user's phone number.
 *
 * @param Request $request
 * @return \Illuminate\Http\JsonResponse
 */
class VerifyEmailController extends Controller
{
    private $otpHandler;

    public function __construct(OtpHandlerRepositoryInterface $otpHandler)
    {
        $this->otpHandler = $otpHandler;
    }

    /**
     * @OA\Post(
     *     path="/api/verify-email",
     *     summary="Verify user's email address using OTP.",
     *     operationId="verifyEmail",
     * security={{"bearerAuth":{}}},
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
     *         description="OTP verified and email status updated.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="OTP verified")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid OTP or expired OTP.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Invalid or OTP Expired")
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
    public function verifyEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'otp' => 'required|string'
        ]);

        $otphandling = $this->otpHandler->verifyOtp($request->email, $request->otp);
        if ($otphandling === false) {
            return response()->json(['message' => 'Invalid or OTP Expired'], 400);
        }

        $email = User::where('email', $request->email)->first();
        if ($email) {
            $email->status = 'verified';
            $email->email_verified_at = now();
            $email->save();
        }

        return response()->json(['message' => 'OTP verified']);
    }
}