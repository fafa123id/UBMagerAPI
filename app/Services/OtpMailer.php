<?php

namespace App\Services;

use App\Mail\OtpMail;
use Illuminate\Support\Facades\Mail;

class OtpMailer
{
    protected $mailer;

    public function sendOtp($email, $otp, $for, $subject)
    {
        return Mail::to($email)->send(new OtpMail($otp, $for, $subject));
    }
}