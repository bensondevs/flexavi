<?php

namespace App\Http\Controllers\Api\Auths;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\Auths\LoginRequest;
use App\Http\Requests\Auths\RegisterRequest;
use App\Http\Requests\Auths\VerifyEmailRequest;
use App\Http\Requests\Auths\CustomerLoginRequest;

use Socialite;

use App\Models\User;

use App\Http\Resources\UserResource;

use App\Repositories\AuthRepository;
use App\Repositories\CompanyOwnerRepository as OwnerRepository;
use App\Repositories\RegisterInvitationRepository;
use App\Repositories\AddressRepository;

class AuthController extends Controller
{
    private $auth;
    private $owner;
    private $address;
    private $invitation;

    public function __construct(
        AuthRepository $auth, 
        OwnerRepository $owner, 
        AddressRepository $address,
        RegisterInvitationRepository $invitation) 
    {
    	$this->auth = $auth;
        $this->owner = $owner;
        $this->address = $address;
        $this->invitation = $invitation;
    }

    public function login(LoginRequest $request)
    {
    	$input = $request->onlyInRules();
    	$user = $this->auth->login($input);
        $user = new UserResource($user);

    	return apiResponse($this->auth, ['user' => $user]); 
    }

    public function customerLogin(CustomerLoginRequest $request)
    {
        $input = $request->validated();
        $customer = $this->auth->customerLogin($input);

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
        // Register User
        $input = $request->userData();
    	$input['profile_picture'] = $request->profile_picture;
    	$user = $this->auth->register($input);

        if ($this->auth->status == 'error') {
            return abort(500, $this->auth->message . '[' . $this->auth->queryError . ']');
        }

        // Handle registration data attached
        if ($invitation = $request->getInvitation()) {
            $this->invitation->setModel($invitation);
            $this->invitation->handleInvitationFulfilled();

            if ($this->invitation->status == 'error') {
                // Revert change
                $user->forceDelete();

                return abort(500, $this->invitation->message . '[' . $this->invitation->queryError . ']');
            }
        }

        // Save address
        $addressData = $request->getAddressData();
        $addressData['user_id'] = $user->id;
        $this->address->save($addressData);

        if ($this->address->status == 'error') {
            // Revert change
            $user->forceDelete();
            return abort(500, $this->address->message . '[' . $this->address->queryError . ']');
        }

        // Send Email Verification
        $this->auth->sendEmailVerification();

    	return apiResponse($this->auth);
    }

    public function verifyEmail(Request $request)
    {
        $code = $request->input('code');
        $this->auth->verifyEmail($code);

        return apiResponse($this->auth);
    }

    public function socialMediaRegister(Request $request, $driver)
    {
        $metaUser = $this->auth->socialiteRegister($driver);

        return apiResponse($this->auth, ['meta_user' => $metaUser]);
    }

    public function logout(Request $request)
    {
        $user = $request->user();
    	$this->auth->setModel($user);
    	$this->auth->logout();

    	return apiResponse($this->auth);
    }

    public function customerLogout(Request $request)
    {
        $user = $request->user();
        $this->auth->customerLogout($user);

        return apiResponse($this->auth);
    }
}
