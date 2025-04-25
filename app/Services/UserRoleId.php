<?php

namespace App\Services;

use App\Models\User;

class UserRoleId
{
    public function getRole(User $user): int
    {
        return $user->role_id;
    }
    public function changeRole (User $usr, int $role): bool
    {
        if (!in_array($role, [0, 1])) {
            throw new \InvalidArgumentException('Invalid role ID');
        }
        $usr->role_id=$role;
        return $usr->save();
    }

}
