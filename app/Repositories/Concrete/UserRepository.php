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

    public function update($id, array $data)
    {
        if (isset($data['email'])) {
            $data['status'] = 'unverified';
            $data['email_verified_at'] = null;
        }
        return new userResource(auth()->user()->update($id, $data));
    }

    public function delete($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return response()->json(['message' => 'User deleted successfully'], 200);
    }
}