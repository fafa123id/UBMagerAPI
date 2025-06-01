<?php

namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;
use App\Http\Resources\failReturn;
use App\Http\Resources\successReturn;
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
     * POST: /api/verify-email
     * Verify the user's email using OTP.
     * This method validates the OTP sent to the user's email and updates the user's email status to verified.
     * @authenticated
     */
    public function verifyEmail(Request $request)
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

        $email = User::where('email', $request->email)->first();
        if ($email) {
            $email->status = 'verified';
            $email->email_verified_at = now();
            $email->save();
        }

        return new successReturn([
            'status' => 200,
            'message' => 'OTP verified'
        ]);
    }
}