<?php

namespace App\Http\Controllers\Api\Auths;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\Auths\{
    LoginRequest,
    RegisterRequest,
    VerifyEmailRequest,
    CustomerLoginRequest,
    ForgotPasswordRequest,
    ResetPasswordRequest,
};

use Socialite;

use App\Models\User;

use App\Http\Resources\UserResource;

use App\Repositories\{
    AuthRepository,
    CompanyOwnerRepository as OwnerRepository,
    RegisterInvitationRepository,
    AddressRepository
};

class AuthController extends Controller
{
    /**
     * Auth repository class container
     * 
     * @var \App\Repositories\AuthRepository
     */
    private $auth;

    /**
     * Owner repository class container
     * 
     * @var \App\Repositories\OwnerRepository
     */
    private $owner;

    /**
     * Address repository class container
     * 
     * @var \App\Repositories\AddressRepository
     */
    private $address;

    /**
     * Register Invitation class container
     * 
     * @var \App\Repositories\RegisterInvitationRepository
     */
    private $invitation;

    /**
     * Controller constructor method
     * 
     * @param \App\Repositories\AuthRepository  $auth
     * @param \App\Repositories\OwnerRepository  $owner
     * @param \App\Repositories\AddressRepository  $address
     * @return Illuminate\Support\Facades\Response
     */
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

    /**
     * Attempt login execution
     * 
     * @param \App\Http\Requests\Auths\LoginRequest  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function login(LoginRequest $request)
    {
    	$input = $request->validated();
        if ($user = $this->auth->login($input)) {
            $user = new UserResource($user);
        }

    	return apiResponse($this->auth, ['user' => $user]); 
    }

    /**
     * Register execution
     * 
     * @param \App\Http\Requests\Auth\RegisterRequest  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function register(RegisterRequest $request)
    {   
        // Register User
        $input = $request->userData();
    	$input['profile_picture'] = $request->profile_picture;
    	$user = $this->auth->register($input);
        $user = $user->fresh();

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
        } else {
            $owner = $this->owner->assignUser($user);

            if ($this->owner->status == 'error' || (! $owner)) {
                // Revert Change
                $user->forceDelete();

                return abort(500, $this->owner->message . '[' . $this->owner->queryError . ']');
            }
        }

        // Save address
        $addressData = $request->getAddressData();
        $this->address->setAddressable($user->role_model);
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

    /**
     * Verify email by sending code given through email confirmation
     * 
     * @param Illuminate\Http\Request  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function verifyEmail(Request $request)
    {
        $code = $request->input('code');
        $this->auth->verifyEmail($code);

        return apiResponse($this->auth);
    }

    /**
     * Resend email verification email
     * 
     * @param Illuminate\Http\Request  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function resendEmailVerification(Request $request)
    {
        $email = $request->email;
        $this->auth->requestVerificationCode($email);

        return apiResponse($this->auth);
    }

    /**
     * Attempt logout for requesting user
     * 
     * @param Illuminate\Http\Request  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function logout(Request $request)
    {
        $user = $request->user();
    	$this->auth->setModel($user);
    	$this->auth->logout();

    	return apiResponse($this->auth);
    }

    /**
     * Attempt forgot password by sending reset password token
     * 
     * @param ForgotPasswordRequest  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function forgotPassword(ForgotPasswordRequest $request)
    {
        $user = $request->getUser();

        $this->auth->setModel($user);
        $this->auth->sendResetPasswordToken();
        
        return apiResponse($this->auth);
    }

    /**
     * Execute reset password after clicking reset password link
     * 
     * @param Illuminate\Http\Request  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function resetPassword(ResetPasswordRequest $request)
    {
        $user = $request->getUser();
        $this->auth->setModel($user);

        $password = $request->input('password');
        $this->auth->changePassword($password);
        $this->auth->claimResetPasswordToken();

        return apiResponse($this->auth);
    }
}
