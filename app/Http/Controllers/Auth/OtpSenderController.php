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
     * @authenticated
     * Send OTP to the user's email for account verification.
     * This method generates a random OTP code and sends it to the user's email for verification purposes.
     */
    public function otpVerifySend(Request $request)
    {
        return $this->sendOtp($request->email, 'verify your account', 'Email Verification');
    }

    /**
     * Send OTP to the user's email for password reset.
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
