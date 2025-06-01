<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Repositories\Abstract\OtpHandlerRepositoryInterface;
use Illuminate\Http\Request;


class OtpSenderController extends Controller
{
    private $otpHandler;

    public function __construct(OtpHandlerRepositoryInterface $otpHandler)
    {
        $this->otpHandler = $otpHandler;
    }
    /**
     * POST: /api/verify-email/send
     * (Send OTP to the user's email for account verification.)
     * This method generates a random OTP code and sends it to the user's email for verification purposes.
     * @authenticated
     */
    public function otpVerifySend(Request $request)
    {
        return $this->sendOtp($request->email, 'verify your account', 'Email Verification');
    }

    /**
     * POST: /api/forgot-password
     * (Send OTP to the user's email for password reset.)
     * This method generates a random OTP code and sends it to the user's email for resetting their password.
     */
    public function otpResetSend(Request $request)
    {
        return $this->sendOtp($request->email, 'reset your password', 'Password Reset');
    }

    private function sendOtp($email, $for, $subject)
    {
        $otpCode = rand(100000, 999999);
        $otphandling = $this->otpHandler->sendOtp($email, $otpCode, $for, $subject);
        return $otphandling;
    }
}
