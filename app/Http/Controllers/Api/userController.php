<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\Abstract\UserRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class userController extends Controller
{

    protected $users;
    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->users = $userRepository;
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
    public function update(Request $request, $id)
    {
        $users = auth()->user();
        if ((int) $id !== (int) $users->id) {
            abort(403, 'Forbidden');
        }
        $validated=$request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|string|email|max:255',
            'phone' => 'sometimes|string|max:255',
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
        return $this->users->update($id, $validated);
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
}
