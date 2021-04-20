<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\Auths\LoginRequest;
use App\Http\Requests\Auths\RegisterRequest;

use App\Repositories\AuthRepository;

class AuthController extends Controller
{
    protected $auth;

    public function __construct(
    	AuthRepository $authRepository
    )
    {
    	$this->auth = $authRepository;
    }

    public function login(LoginRequest $request)
    {
    	$input = $request->onlyInRules();
    	$user = $this->auth->login($input);

    	return apiResponse($this->auth, $user); 
    }

    public function register(RegisterRequest $request)
    {
    	$input = $request->onlyInRules();
    	$input['profile_picture_url'] = uploadFile(
			$request->file('profile_picture'), 
			'storage/profile_pictures/'
		);
    	$user = $this->auth->register($input);

    	return apiResponse($this->auth, $user);     
    }

    public function logout()
    {
    	$this->auth->setModel(auth()->user());
    	$this->auth->logout();

    	return response()->json([
    		'status' => $this->auth->status,
    		'message' => $this->auth->message,
    	]);
    }
}
