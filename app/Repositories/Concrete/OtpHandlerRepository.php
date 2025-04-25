<?php
namespace App\Repositories\Concrete;

use App\Models\otp;
use App\Repositories\Abstract\OtpHandlerRepositoryInterface;
use App\Services\OtpMailer;
use Illuminate\Support\Facades\Hash;


class OtpHandlerRepository implements OtpHandlerRepositoryInterface
{
    private $mail;
    public function __construct(OtpMailer $mail)
    {
        $this->mail = $mail;
    }
    public function sendOtp($email, $otp, $for, $subject)
    {
        // Cek apakah sudah ada OTP yang belum selesai
        $existingOtp = Otp::where('email', $email)
        ->first();

        if ($existingOtp) {
            $existingOtp->delete(); // hapus OTP yang sudah ada
        }
        $emailSend = $this->mail->sendOtp($email, $otp, $for, $subject);
        if ($emailSend===null) {
            return false;
        }
        // Simpan OTP ke database
        Otp::create([
            'email' => $email,
            'otp' => Hash::make($otp),
            'status' => 'sent',
            'expires_at' => now()->addMinutes(5),
        ]);
        return true;
    }

    public function verifyOtp($email, $otp)
    {
        $otpCheck = otp::where('email', $email)->first();

        if (!$otpCheck) {
            return false;
        }

        if (!Hash::check($otp, $otpCheck->otp)) {
            return false;
        }

        $otpCheck->delete(); // hapus OTP setelah diverifikasi
        return true;
    }
}