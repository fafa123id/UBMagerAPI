<?php

namespace App\Policies;

use App\Models\User;
use App\Models\otp;
use Illuminate\Auth\Access\Response;

class OtpPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, otp $otp): bool
    {
        return $user->phone->phone === $otp->phone;
    }

    /**
     * Determine whether the user can create models.
     */
    public function createotp(User $user, $phone): bool
    {
        dd($user->phone->phone . ' ' . $phone);
        return $user->phone->phone === $phone;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, otp $otp): bool
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, otp $otp): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, otp $otp): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, otp $otp): bool
    {
        return false;
    }
    /**
     * Determine whether the user can verify the OTP.
     */
    public function verify(User $user, Otp $otp)
    {
        return $user->phone === $otp->phone && $otp->status === 'pending';
    }
}
