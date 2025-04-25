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
    public function otpVerifySend(Request $request){
        $sendOtp = $this->sendOtp($request->email, 'verify your account', 'Email Verification');
        if ($sendOtp === false) {
            return response()->json(['message' => 'Failed to send OTP'], 500);
        }
        return response()->json(['message' => 'OTP sent successfully']);
    }
    public function otpResetSend(Request $request){
        $sendOtp = $this->sendOtp($request->email, 'reset your password', 'Password Reset');
        if ($sendOtp === false) {
            return response()->json(['message' => 'Failed to send OTP'], 500);
        }
        return response()->json(['message' => 'OTP sent successfully']);
    }
    private function sendOtp($email, $for, $subject)
    {
        
        $otpCode = rand(100000, 999999);
        $otphandling=$this->otpHandler->sendOtp($email, $otpCode, $for, $subject);
        if ($otphandling === false) {
            return false;
        }
        return true;
    }

}
