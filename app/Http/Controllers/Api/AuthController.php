<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\Auths\LoginRequest;
use App\Http\Requests\Auths\RegisterRequest;
use App\Http\Requests\Auths\VerifyEmailRequest;
use App\Http\Requests\Auths\CustomerLoginRequest;

use Socialite;

use App\Models\User;

use App\Repositories\AuthRepository;
use App\Repositories\CompanyOwnerRepository as OwnerRepository;

class AuthController extends Controller
{
    private $auth;
    private $owner;

    public function __construct(
    	AuthRepository $auth,
        OwnerRepository $owner
    )
    {
    	$this->auth = $auth;
        $this->owner = $owner;
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

        return apiResponse($this->auth, ['customer' => $customer]);
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
        $user = $this->auth->socialiteLogin($socialiteUser);

        return apiResponse($this->auth, ['user' => $user]);
    }

    public function register(RegisterRequest $request)
    {
        if ($invitation = $request->getInvitation()) {
            if ($invitation->invited_email !== $request->input('email'))
                return response()->json(['message' => 'This invitation is not for this email'], 403);
        }

    	$input = $request->userData();
        if (! $attachments = $request->getAttachments()) {
            $owner = $this->owner->save($request->getOwnerData());
            $attachments = [
                'model' => 'App\Models\Owner',
                'model_id' => $owner->id,
                'related_column' => 'user_id',
                'role' => 'owner',
            ];
        }

    	$user = new User();
        $user->profile_picture = $request->file('profile_picture');
        $user = $this->auth->setModel($user);
    	$user = $this->auth->register($input, $attachments);

    	return apiResponse($this->auth, ['user' => $user]);
    }

    public function verifyEmail(VerifyEmailRequest $request)
    {

    }

    public function socialMediaRegister(Request $request, $driver)
    {
        $metaUser = $this->auth->socialiteRegister($driver);

        return apiResponse($this->auth, ['meta_user' => $metaUser]);
    }

    public function logout()
    {
    	$this->auth->setModel(auth()->user());
    	$this->auth->logout();

    	return apiResponse($this->auth);
    }

    public function customerLogout()
    {
        $this->auth->customerLogout(auth()->user());

        return apiResponse($this->auth);
    }
}
