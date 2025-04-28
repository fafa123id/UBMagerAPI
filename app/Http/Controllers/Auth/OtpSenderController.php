<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Repositories\Abstract\OtpHandlerRepositoryInterface;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="OTP",
 *     description="API Endpoints for sending OTP for verification and password reset"
 * )
 */
class OtpSenderController extends Controller
{
    private $otpHandler;

    public function __construct(OtpHandlerRepositoryInterface $otpHandler)
    {
        $this->otpHandler = $otpHandler;
    }

    /**
   
     * @OA\Post(
     *     path="/api/verify-email/send",
     *     summary="Send OTP for email verification",
     *     tags={"OTP"},
     * security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email"},
     *             @OA\Property(property="email", type="string", format="email", example="user@example.com")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OTP sent successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="OTP sent successfully")
     *         )
     *     ),
     * @OA\Response(
     *         response=401,
     *         description="Unauthenticated"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Failed to send OTP",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Failed to send OTP")
     *         )
     *     )
     * )
     */

    public function otpVerifySend(Request $request)
    {
        $sendOtp = $this->sendOtp($request->email, 'verify your account', 'Email Verification');
        if ($sendOtp === false) {
            return response()->json(['message' => 'Failed to send OTP'], 500);
        }
        return response()->json(['message' => 'OTP sent successfully']);
    }

    /**
     * @OA\Post(
     *     path="/api/forgot-password",
     *     summary="Send OTP for password reset",
     *     tags={"OTP"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email"},
     *             @OA\Property(property="email", type="string", format="email", example="user@example.com")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OTP sent successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="OTP sent successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Failed to send OTP",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Failed to send OTP")
     *         )
     *     )
     * )
     */
    public function otpResetSend(Request $request)
    {
        $sendOtp = $this->sendOtp($request->email, 'reset your password', 'Password Reset');
        if ($sendOtp === false) {
            return response()->json(['message' => 'Failed to send OTP'], 500);
        }
        return response()->json(['message' => 'OTP sent successfully']);
    }

    private function sendOtp($email, $for, $subject)
    {
        $otpCode = rand(100000, 999999);
        $otphandling = $this->otpHandler->sendOtp($email, $otpCode, $for, $subject);
        if ($otphandling === false) {
            return false;
        }
        return true;
    }
}
