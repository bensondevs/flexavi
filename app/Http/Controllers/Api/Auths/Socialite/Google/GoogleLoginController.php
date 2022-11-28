<?php

namespace App\Http\Controllers\Api\Auths\Socialite\Google;

use App\Enums\User\UserSocialiteAccountType;
use App\Http\Controllers\Controller;
use App\Http\Resources\Users\UserResource;
use App\Repositories\Permission\PermissionRepository;
use App\Services\Google\AuthService;
use Illuminate\Http\JsonResponse;
use Laravel\Socialite\Facades\Socialite;

class GoogleLoginController extends Controller
{
    /**
     * Auth service container variable
     *
     * @var AuthService
     */
    private AuthService $authService;

    /**
     * Controller constructor method
     *
     * @param AuthService $authService
     * @param PermissionRepository $permissionRepository
     */
    public function __construct(
        AuthService $authService,
        PermissionRepository $permissionRepository,
    ) {
        $this->authService = $authService;
        $this->permissionRepository = $permissionRepository;
    }

    /**
     * Get redirect of google auth
     *
     * @return JsonResponse
     */
    public function redirect(): JsonResponse
    {
        $url = $this->authService->loginRedirectUrl();
        return response()->json([
            'status' => true,
            'message' => 'Successfully to get redirect url',
            'data' => [
                'url' => $url,
                'provider' => UserSocialiteAccountType::getDescription(UserSocialiteAccountType::Google)
            ]
        ]);
    }

    /**
     * Login callback
     *
     * @return JsonResponse
     */
    public function callback(): JsonResponse
    {
        config([
            'services.google.redirect' => config('app.frontend_url') . '/login'
        ]);
        $socialite = Socialite::driver('google')
            ->stateless()
            ->user();
        $user = $this->authService->loginCallback($socialite);
        return response()->json([
            'status' => true,
            'message' => 'Successfully to login',
            'data' => [
                'user' => new UserResource($user),
            ],
        ]);
    }
}
