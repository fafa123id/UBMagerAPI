<?php

namespace App\Services;

use App\Mail\ResetPasswordMail;
use Illuminate\Support\Facades\Mail;

class ResetPwMailer
{
    protected $mailer;

    public function sendResetPw($email, $otp, $for, $subject)
    {
        return Mail::to($email)->queue(new ResetPasswordMail($otp, $for, $subject));
    }
}