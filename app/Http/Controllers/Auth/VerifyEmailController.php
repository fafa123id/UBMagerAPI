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
