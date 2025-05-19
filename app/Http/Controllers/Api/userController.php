<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\Abstract\UserRepositoryInterface;
use Illuminate\Http\Request;

class userController extends Controller
{
    
    protected $users;
    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->users = $userRepository;
    }

    
    public function index()
    {
        return $this->users->self();
    }

    
    public function show($id)
    {
        return $this->users->find($id);
    }

   
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'phone' => 'required|string|max:255',
        ]);

        return $this->users->update($id, $request->all());
    }
}