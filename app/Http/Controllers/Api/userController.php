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
     * Display the authenticated user's profile.
     * This method retrieves the profile of the authenticated user.
     * @authenticated
     */
    public function index()
    {
        return $this->users->self();
    }

    /**
     * Display a specific user by ID.
     * This method retrieves a user by their ID.
     */
    public function show($id)
    {
        return $this->users->find($id);
    }

    /**
     * Update the authenticated user's profile.
     * This method allows the authenticated user to update their profile information.
     * @authenticated
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|string|email|max:255',
            'phone' => 'sometimes|string|max:255',
            'address' => 'sometimes|string|max:255',
            'image' => 'sometimes|image|mimes:jpeg,png,jpg|max:2048',
        ]);
        $users = auth()->user();
        if ($request->hasFile('image')) {
            // Delete the old image from S3 if it exists
            if ($users->image && Storage::disk('s3')->exists($users->image)) {
                Storage::disk('s3')->delete($users->image);
            }
            $imagePath = config('filesystems.disks.s3.url') . $request->file('image')->store('images', 's3');
            $request->merge(['image' => $imagePath]);
        }
        dd($request->all());
        return $this->users->update($id, $request->all());
    }
}