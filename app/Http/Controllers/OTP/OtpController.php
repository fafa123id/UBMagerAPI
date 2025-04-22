<?php

namespace App\Http\Controllers\OTP;
use App\Http\Controllers\Controller;
use App\Models\otp;
use App\Models\User;
use App\Services\OtpMailer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;

 /**
     * Send OTP to the user's phone number.
     *
     * @param Request $request
     * @param Twilio $twilio
     * @return \Illuminate\Http\JsonResponse
     */
class OtpController extends Controller
{
    private $mail;
    public function __construct(OtpMailer $mail)
    {
        $this->mail = $mail;
    }
    public function sendSMS(Request $request)
    {
        $request->validate([
            'email' => 'required|string'
        ]);
        
        // Cek apakah sudah ada OTP yang belum selesai
        $existingOtp = Otp::where('email', $request->email)
        ->where('expires_at', '>', now()) // masih aktif
        ->first();

        if ($existingOtp) {
            $existingOtp->delete(); // hapus OTP yang sudah ada
        }
        $otpCode = rand(100000, 999999);

        $email = $this->mail->sendOtp($request->email, $otpCode);
        if ($email===null) {
            return response()->json(['message' => 'Failed to send OTP'], 500);
        }
        // Simpan OTP ke database
        Otp::create([
            'email' => $request->email,
            'otp' => Hash::make($otpCode),
            'status' => 'sent',
            'expires_at' => now()->addMinutes(5),
        ]);

        return response()->json(['message' => 'OTP sent']);
    }
    public function verifySMS(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'otp' => 'required|string'
        ]);

        $otp = otp::where('email', $request->email)->first();

        if (!$otp) {
            return response()->json(['message' => 'OTP Expired'], 404);
        }

        if (!Hash::check($request->otp, $otp->otp)) {
            return response()->json(['message' => 'Invalid OTP'], 400);
        }

        // Update the phone status to verified
        $email= User::where('email', $request->email)->first();
        if ($email) {
            $email->status = 'verified';
            $email->email_verified_at = now();
            $email->save();
        }
        $otp->delete();

        return response()->json(['message' => 'OTP verified']);
    }
}
