<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Repositories\UserRepository;

class UserController extends Controller
{
    protected $user;

    public function __construct(UserRepository $userRepository)
    {
    	$this->user = $userRepository;
    }

    public function populate()
    {
    	$users = $this->user->all();
    }

    public function currentUser()
    {
    	return response()->json([
    		'user' => auth()->user()
    	]);
    }
}
