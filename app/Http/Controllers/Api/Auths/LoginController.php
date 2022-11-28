<?php

namespace App\Http\Controllers\Api\Auths;

use App\Http\Controllers\Controller;
use App\Http\Requests\Company\Auths\LoginRequest;
use App\Http\Resources\Users\UserResource;
use App\Repositories\Auths\AuthRepository;
use Illuminate\Http\JsonResponse;

class LoginController extends Controller
{
    /**
     * Auth repository container variable
     *
     * @var AuthRepository
     */
    private AuthRepository $authRepository;

    /**
     * Controller constructor method
     *
     * @param AuthRepository $authRepository
     */
    public function __construct(
        AuthRepository $authRepository,
    ) {
        $this->authRepository = $authRepository;
    }

    /**
     * Attempt login execution
     *
     * @param LoginRequest $request
     * @return JsonResponse
     * @see \Tests\Feature\Auth\Login\LoginTest::test_login_as_owner()
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $input = $request->validated();
        $user = $this->authRepository->login($input);
        $user = $user ? new UserResource($user) : null;

        return apiResponse($this->authRepository, [
            'user' => $user,
        ]);
    }
}
