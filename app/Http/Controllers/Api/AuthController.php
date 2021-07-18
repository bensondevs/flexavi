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

use App\Http\Resources\UserResource;

use App\Repositories\AuthRepository;
use App\Repositories\CompanyOwnerRepository as OwnerRepository;
use App\Repositories\AddressRepository;

class AuthController extends Controller
{
    private $auth;
    private $owner;
    private $address;

    public function __construct(
    	AuthRepository $auth,
        OwnerRepository $owner,
        AddressRepository $address
    )
    {
    	$this->auth = $auth;
        $this->owner = $owner;
        $this->address = $address;
    }

    public function checkEmailUsed(Request $request)
    {
        $request->validate(['email' => ['required', 'email']]);

        $email = $request->input('email');
        if (db('users')->where('email', $email)->count() > 0) {
            return response()->json([
                'status' => 'unavailable',
                'message' => 'This email has been used by other user',
            ], 422);
        }

        return response()->json([
            'status' => 'available',
            'message' => 'This email is available',
        ]);
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
        $input = $request->onlyInRules();
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
        if ($invitation = $request->getInvitation()) {
            if ($invitation->invited_email !== $request->input('email'))
                return response()->json(['message' => 'This invitation is not for this email'], 403);
        }

    	$input = $request->userData();
        if (! $attachments = $request->getAttachments()) {
            $owner = $this->owner->save($request->getOwnerData());
            if ($this->owner->status == 'error') {
                abort(500, $this->owner->queryError);
            }

            $attachments = [
                'model' => 'App\Models\Owner',
                'model_id' => $owner->id,
                'related_column' => 'user_id',
                'role' => 'owner',
            ];
        }

        // Register User
    	$user = new User();
        if ($profilePicture = $request->profile_picture) {
            $user->profile_picture = $profilePicture;
        }
        $user = $this->auth->setModel($user);
    	$user = $this->auth->register($input, $attachments);

        // Save address
        $addressData = $request->getAddressData();
        $addressData['user_id'] = $user->id;
        $this->address->save($addressData);

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
