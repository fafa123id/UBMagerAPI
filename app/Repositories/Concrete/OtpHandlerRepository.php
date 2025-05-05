<?php
namespace App\Repositories\Concrete;

use App\Http\Resources\failReturn;
use App\Http\Resources\successReturn;
use App\Models\otp;
use App\Repositories\Abstract\OtpHandlerRepositoryInterface;
use App\Services\OtpMailer;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;


class OtpHandlerRepository implements OtpHandlerRepositoryInterface
{
    private $mail;
    public function __construct(OtpMailer $mail)
    {
        $this->mail = $mail;
    }
    private function createCache($email){
        $ip = request()->ip();

        // Buat cache key unik untuk throttle
        $key = 'otp_throttle:' . sha1($email . '|' . $ip); 

        // Cek apakah throttle masih aktif
        if (Cache::has($key)) {
            return new failReturn([
                'status' => 429,
                'message' => 'Please wait before requesting another OTP'
            ]);
        }
        Cache::put($key, true, now()->addSeconds(60));
        return false;
    }
    public function sendOtp($email, $otp, $for, $subject)
    {
        $cache = $this->createCache($email);
        if ($cache) {
            return $cache;
        }
        // Cek apakah sudah ada OTP yang belum selesai
        $existingOtp = Otp::where('email', $email)
            ->first();

        if ($existingOtp) {
            $existingOtp->delete(); // hapus OTP yang sudah ada
        }
        $emailSend = $this->mail->sendOtp($email, $otp, $for, $subject);
        if ($emailSend === null) {
            return new failReturn([
                'status' => 500,
                'message' => 'Failed to send OTP'
            ]);
        }
        // Simpan OTP ke database
        Otp::create([
            'email' => $email,
            'otp' => Hash::make($otp),
            'status' => 'sent',
            'expires_at' => now()->addMinutes(5),
        ]);
        return new successReturn([
            'status' => 200,
            'message' => 'OTP sent successfully',
        ]);
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

        $otpCheck->delete();
        return true;
    }
}