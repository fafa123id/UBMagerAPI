<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\Abstract\OtpHandlerRepositoryInterface;
use App\Repositories\Abstract\UserRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class userController extends Controller
{

    protected $users;
    protected $otpHandler;
    public function __construct(UserRepositoryInterface $userRepository, OtpHandlerRepositoryInterface $otpHandlerRepository)
    {
        $this->users = $userRepository;
        $this->otpHandler = $otpHandlerRepository;
    }

    /**
     * GET: /api/user
     * 
     * Display the authenticated user's profile.
     * This method retrieves the profile of the authenticated user.
     * @authenticated
     */
    public function index()
    {
        return $this->users->self();
    }

    /**
     * GET: /api/user/{id}
     * 
     * Display a specific user by ID.
     * This method retrieves a user by their ID.
     */
    public function show($id)
    {
        return $this->users->find($id);
    }

    /**
     * PUT: /api/user/{id}
     * 
     * Update the authenticated user's profile.
     * This method allows the authenticated user to update their profile information.
     * @authenticated
     */
    public function newEmail(Request $request)
    {
        $user = auth()->user();
        $validated = $request->validate([
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
        ]);
        return $this->users->update($user->id, $validated, $user->email);
    }
    public function sendChangeEmailOtp(Request $request)
    {
        $user = auth()->user();
        if (!$user->email) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'No email associated with this account'
                ],
                400
            );
        }
        if (!$user->email_verified_at) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Please verify your email before changing it'
                ],
                400
            );
        }
        return $this->otpHandler->sendOtp($user->email,rand(100000, 999999), 'Change Email', 'Change Email Request');
    }
    public function changeEmail(Request $request)
    {
        $user = auth()->user();
        $validated = $request->validate([
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'otp' => 'required|string|max:6',
        ]);
        $otpValid = $this->otpHandler->verifyOtp($user->email, $validated['otp']);
        if (!$otpValid) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Invalid OTP code'
                ],
                400
            );
        }
        unset($validated['otp_code']);
        return $this->users->update($user->id, $validated, $user->email);
    }
    public function update(Request $request, $id)
    {
        $users = auth()->user();
        if ((int) $id !== (int) $users->id) {
            abort(403, 'Forbidden');
        }
        $oldEmail = $users->email??"null";
        $validated=$request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|nullable|string|email|max:255|unique:users,email,' . $id,
            'phone' => 'sometimes|nullable|string|max:255|unique:users,phone,' . $id,
            'address' => 'sometimes|string|max:255',
            'image' => 'sometimes|image|mimes:jpeg,png,jpg|max:2048',
            'username' => 'sometimes|string|max:255|unique:users,username,' . $id,
            'bio' => 'sometimes|nullable|string|max:255',
        ]);
        if ($request->hasFile('image')) {
            // Delete the old image from S3 if it exists
            if ($users->image && Storage::disk('s3')->exists($users->image)) {
                Storage::disk('s3')->delete($users->image);
            }
            $imagePath =$request->file('image')->store('images', 's3');
            $validated['image'] = $imagePath;
        }
        return $this->users->update($id, $validated, $oldEmail);
    }
    /**
     * GET: /api/be-mitra
     *
     * Change the authenticated user's role to seller.
     * @authenticated
     */
    public function changeRole()
    {
        auth()->user()->update(['role_id' => 1]);
        return response()->json(
            [
                'success' => true,
                'message' => 'Role changed successfully'
            ],
            200
        );
    }
    public function addPassword(Request $request)
    {
        $validated = $request->validate([
            'password' => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required|string|min:8',
        ]);
        $user = auth()->user();
        if ($user->password) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Password already set'
                ],
                400
            );
        }
        $user->password = Hash::make($validated['password']);
        $user->save();
        return response()->json(
            [
                'success' => true,
                'message' => 'Password added successfully'
            ],
            200
        );
    }
}
