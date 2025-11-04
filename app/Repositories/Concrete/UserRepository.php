<?php

namespace App\Repositories\Concrete;
use App\Http\Resources\userResource;
use App\Models\User;
use App\Repositories\Abstract\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface
{
    public function self()
    {
        return new userResource(auth()->user());
    }

    public function find($id)
    {
        return new userResource(User::findOrFail($id));
    }

    public function update($id, array $data, string $oldEmail)
    {
        $user = User::findOrFail($id);
        if (($data["email"] ?? null) != $oldEmail) {
            $data['status'] = 'unverified';
            $data['email_verified_at'] = null;
        }
        $user->update($data);
        return new userResource($user);
    }
    

    public function delete($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return response()->json(['message' => 'User deleted successfully'], 200);
    }
}