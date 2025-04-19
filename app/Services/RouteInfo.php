<?php

namespace App\Services;

use App\Models\User;
class RouteInfo
{
    private $info= [
        ['uri' => 'api/register', 'description' => 'Register a new user, param nya (name, email, password, password_confirmation'],
        ['uri' => 'api/login', 'description' => 'Login user and get token param nya (email, password)'],
        ['uri' => 'api/forgot-password', 'description' => 'Send password reset link to email'],
        ['uri' => 'api/reset-password', 'description' => 'Reset user password with token'],
        ['uri' => 'api/verify-email/{id}/{hash}', 'description' => 'Verify user email using ID and hash'],
        ['uri' => 'api/email/verification-notification', 'description' => 'Resend email verification notification'],
        ['uri' => 'api/logout', 'description' => 'Logout the authenticated user, pakai bearer token'],
        ['uri' => 'api/user', 'description' => 'Get User, Harus Login dulu pake sanctum (get Token), abis itu akses api ini dengan bearer token'],
    ];
    public function getInfo(){
        return $this->info;
    }
}
