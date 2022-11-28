<?php

namespace App\Http\Controllers\Api\Auths;

use App\Http\Controllers\Controller;
use App\Http\Requests\Company\Auths\Customer\{CustomerResetUniqueKeyRequest as ResetUniqueKeyRequest};
use App\Http\Requests\Company\Auths\Customer\CustomerLoginRequest as LoginRequest;
use App\Repositories\Auths\CustomerAuthRepository as AuthsCustomerAuthRepository;
use Illuminate\Http\Request;

class CustomerAuthController extends Controller
{
    /**
     * Customer Auth Repository Class Container
     *
     * @var \App\Repositories\Auths\CustomerAuthRepository
     */
    private $customerAuth;

    /**
     * Controller constructor method
     *
     * @param \App\Repositories\Auths\CustomerAuthRepository  $customerAuth
     * @return void
     */
    public function __construct(AuthsCustomerAuthRepository $customerAuth)
    {
        $this->customerAuth = $customerAuth;
    }

    /**
     * Attemp customer login request
     *
     * @param LoginRequest  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();
        $this->customerAuth->login($credentials);

        return apiResponse($this->customerAuth);
    }

    /**
     * Reset customer login unique key
     *
     * @param ResetUniqueKeyRequest  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function resetUniqueKey(ResetUniqueKeyRequest $request)
    {
        $customer = $request->getCustomer();

        $customer = $this->customerAuth->setModel($customer);
        $customer = $this->customerAuth->resetUniqueKey();

        $uniqueKey = $customer->unique_key;
        return apiResponse($this->customerAuth, ['unique_key' => $uniqueKey]);
    }

    /**
     * Attemp logout for requesting customer
     *
     * @param Illuminate\Http\Request  $request
     * @return Illuminate\Support\Facades\Response
     */
    public function logout(Request $request)
    {
        $customer = $request->user();
        $this->customerAuth->customerLogout($customer);

        return apiResponse($this->customerAuth);
    }
}
