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

class RegisteredUserController extends Controller
{
    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): JsonResponse
    {
        $request->merge([
            'role_id' => $request->role_id ?? 0,
        ]);
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role_id' => ['required', 'integer'],
            'phone' => ['required', 'string', 'unique:users,phone','regex:/^(\+62|62|0)8[1-9][0-9]{6,9}$/','max:15'],
        ]);
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role_id'=> $request->role_id,
            'phone' => $request->phone,
            'password' => Hash::make($request->string('password')),
            
        ]);

        event(new Registered($user));

        

        return response()->json([
            'message'=>'Register Berhasil'
        ],201);
    }
}
