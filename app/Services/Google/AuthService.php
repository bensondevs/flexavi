<?php

namespace App\Services\Google;

use App\Enums\User\UserSocialiteAccountType;
use App\Models\User\User;
use App\Models\User\UserSocialiteAccount;
use Auth;

class AuthService
{
    /**
     * Get google redirect login url
     *
     * @return string
     */
    public function loginRedirectUrl(): string
    {
        config([
            'services.google.redirect' => config('app.frontend_url') . '/login'
        ]);
        return \Socialite::driver('google')
            ->stateless()
            ->redirect()
            ->getTargetUrl();
    }

    /**
     * Get register redirect google url
     *
     * @return string
     */
    public function registerRedirectUrl(): string
    {
        config([
            'services.google.redirect' => config('app.frontend_url') . '/registration'
        ]);
        return \Socialite::driver('google')
            ->stateless()
            ->redirect()
            ->getTargetUrl();
    }

    /**
     * Handle callback of google login auth
     *
     * @param mixed $socialite
     * @return User
     */
    public function loginCallback(mixed $socialite): User
    {
        $user = User::whereEmail($socialite->getEmail())->first();
        if (!$user) {
            abort(422, 'User is not registered yet, please register first.');
        }

        $provider = UserSocialiteAccount::whereUserId($user->id)
            ->whereVendorUserId($socialite->getId())
            ->whereType(UserSocialiteAccountType::Google)
            ->first();
        if (!$provider) {
            abort(422, 'User is not registered using Google.');
        }

        $this->generateTokenWithLogin($user);

        return $user;
    }

    /**
     * Generate API token with login
     *
     * @param User $user
     * @return void
     */
    public function generateTokenWithLogin(User $user): void
    {
        $user->generateToken();
        Auth::login($user);
    }
}
