<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\Auths\LoginRequest;
use App\Http\Requests\Auths\RegisterRequest;
use App\Http\Requests\Auths\CustomerLoginRequest;

use Socialite;

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
        $user->role = $user->user_role;

    	return apiResponse($this->auth, ['user' => $user]); 
    }

    public function customerLogin(CustomerLoginRequest $request)
    {
        $customer = $this->auth->customerLogin(
            $request->onlyInRules()
        );

        return apiResponse($this->auth, $customer);
    }

    public function socialMediaLoginRedirect(Request $request, $driver)
    {
        return Socialite::driver($driver)
            ->stateless()
            ->redirect()
            ->getTargetUrl();
    }

    public function socialMediaLoginCallback(Request $request, $driver)
    {
        $socialiteUser = Socialite::driver($driver)
            ->stateless()
            ->user();
        $this->auth->socialiteLogin($socialiteUser);

        return apiResponse(
            $this->auth, 
            $this->auth->getModel()
        );
    }

    public function register(RegisterRequest $request)
    {
    	$input = $request->onlyInRules();
    	$input['profile_picture_url'] = uploadFile(
			$request->file('profile_picture'), 
			'storage/profile_pictures/'
		);
    	$user = $this->auth->register(
            $input, 
            $request->getInvitation()
        );

    	return apiResponse($this->auth, $user);     
    }

    public function socialMediaRegister(Request $request, $driver)
    {
        $metaUser = $this->auth->socialiteRegister($driver);

        return apiResponse(
            $this->auth, 
            $metaUser
        );
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

    public function customerLogout()
    {
        $this->auth->customerLogout(auth()->user());

        return response()->json([
            'status' => $this->auth->status,
            'message' => $this->auth->message,
        ]);
    }
}
