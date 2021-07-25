<?php

namespace App\Http\Controllers\Api\Auths;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\Auths\Customer\CustomerLoginRequest as LoginRequest;
use App\Http\Requests\Auths\Customer\CustomerResetUniqueKeyRequest as ResetUniqueKeyRequest;

use App\Repositories\CustomerAuthRepository;

class CustomerAuthController extends Controller
{
    private $customerAuth;

    public function __construct(CustomerAuthRepository $customerAuth)
    {
        $this->customerAuth = $customerAuth;
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();
        $this->customerAuth->login($credentials);

        return apiResponse($this->customerAuth);
    }

    public function resetUniqueKey(ResetUniqueKeyRequest $request)
    {
        $customer = $request->getCustomer();

        $customer = $this->customerAuth->setModel($customer);
        $customer = $this->customerAuth->resetUniqueKey();

        $uniqueKey = $customer->unique_key;
        return apiResponse($this->customerAuth, ['unique_key' => $uniqueKey]);
    }

    public function logout(Request $request)
    {
        $customer = $request->user();
        $this->customerAuth->customerLogout($customer);

        return apiResponse($this->customerAuth);
    }
}
