<?php

namespace App\Http\Controllers\Api\Auths\Socialite\Google;

use App\Enums\User\UserSocialiteAccountType;
use App\Http\Controllers\Controller;
use App\Models\User\User;
use App\Services\Google\AuthService;
use Illuminate\Http\JsonResponse;
use Socialite;

class GoogleRegisterController extends Controller
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
     */
    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * Get redirect of google auth
     *
     * @return JsonResponse
     */
    public function redirect(): JsonResponse
    {
        $url = $this->authService->registerRedirectUrl();

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
     * Register callback
     *
     * @return JsonResponse
     */
    public function callback(): JsonResponse
    {
        try {
            config([
                'services.google.redirect' => config('app.frontend_url') . '/registration'
            ]);
            $socialite = Socialite::driver('google')
                ->stateless()
                ->user();

            if (User::whereEmail($socialite->getEmail())->exists()) {
                abort(422, 'User already registered.');
            }

            return response()->json([
                'status' => true,
                'message' => 'Successfully to get information from google',
                'data' => [
                    'user' => [
                        'provider' => UserSocialiteAccountType::Google,
                        'provider_description' => UserSocialiteAccountType::getDescription(UserSocialiteAccountType::Google),
                        'provider_id' => $socialite->getId(),
                        'email' => $socialite->getEmail(),
                        'name' => $socialite->getName(),
                        'profile_picture' => $socialite->getAvatar()
                    ],
                ]
            ]);
        } catch (\Exception $e) {
            $message = "Session expired, please register with google again";
            if ($e->getCode() === 422) {
                $message = $e->getMessage();
            }
            abort($e->getCode(), $message);
        }
    }
}
