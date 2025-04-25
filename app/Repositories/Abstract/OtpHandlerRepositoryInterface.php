<?php
namespace App\Repositories\Abstract;

interface OtpHandlerRepositoryInterface
{
   public function sendOtp($email, $otp, $for, $subject);
   public function verifyOtp($email, $otp);
}

