<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use OpenApi\Annotations as OA;

class RegisteredUserController extends Controller
{
    /**
     * Register a new user.
     * This method creates a new user, hashes the password, and generates an API token for the user.
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): JsonResponse
    {
        $request->merge([
            'role_id' => $request->role_id ?? 0,
        ]);

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role_id' => ['nullable', 'integer'],
            'phone' => ['required', 'string', 'unique:users,phone', 'regex:/^(\+62|62|0)8[1-9][0-9]{6,9}$/', 'max:15'],
            'address' => ['required', 'string', 'max:255'],
            'image' => ['nullable','image','mimes:jpeg,png,jpg','max:2048'],
        ]);
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = config('filesystems.disks.s3.url').$request->file('image')->store('images', 's3');
        }
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role_id' => $request->role_id?? 0,
            'phone' => $request->phone,
            'password' => Hash::make($request->string('password')),
            'address' => $request->address,
            'image' => $imagePath ?? 'https://static.vecteezy.com/system/resources/thumbnails/020/765/399/small/default-profile-account-unknown-icon-black-silhouette-free-vector.jpg',
        ]);

        return response()->json([
            'message' => 'Register Berhasil'
        ], 201);
    }
}
