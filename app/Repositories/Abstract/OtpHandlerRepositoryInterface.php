<?php
namespace App\Repositories\Abstract;

interface OtpHandlerRepositoryInterface
{
   public function sendOtp($email, $otp, $for, $subject, $otpcache);
   public function verifyOtp($email, $otp);
}

