<?php

namespace Tests\Shared;

use Laravel\Socialite\Contracts\Factory as Socialite;
use Laravel\Socialite\Two\GoogleProvider;

trait MockSocialite
{
    /**
     * Mock socialite method
     *
     * @param int $id
     * @param string $email
     * @param string $name
     * @return void
     */
    public function mockSocialite(int $id, string $email, string $name): void
    {
        $socialiteUser = $this->createMock(\Laravel\Socialite\Two\User::class);
        $socialiteUser->id = $id;
        $socialiteUser->email = $email;
        $socialiteUser->name = $name;

        $socialiteUser->expects($this->any())
            ->method('getId')
            ->willReturn($id);
        $socialiteUser->expects($this->any())
            ->method('getName')
            ->willReturn($name);
        $socialiteUser->expects($this->any())
            ->method('getEmail')
            ->willReturn($email);
        $socialiteUser->expects($this->any())
            ->method('getAvatar')
            ->willReturn('https://lh3.googleusercontent.com/a/AATXAJyjvc3Ab4YyUA-vI8hkMVwxX-RUAdzw-PWSYNRL=s96-c');

        $provider = $this->createMock(GoogleProvider::class);

        $provider->expects($this->any())
            ->method('user')
            ->willReturn($socialiteUser);

        $provider->expects($this->any())
            ->method('stateless')
            ->willReturn($provider);

        $provider->expects($this->any())
            ->method('redirectUrl')
            ->willReturn(config('services.google.login_redirect'));

        $stub = $this->createMock(Socialite::class);
        $stub->expects($this->any())
            ->method('driver')
            ->willReturn($provider);

        // Replace Socialite Instance with our mock
        $this->app->instance(Socialite::class, $stub);
    }

    /**
     * Mock socialite exception method
     *
     * @return void
     */
    public function mockSocialiteException(): void
    {
        $provider = $this->createMock(GoogleProvider::class);
        $provider->expects($this->any())
            ->method('user')->willReturn($provider);

        $provider->expects($this->any())
            ->method('stateless')
            ->willReturn($provider);

        $stub = $this->createMock(Socialite::class);
        $stub->expects($this->any())
            ->method('driver')
            ->willReturn($provider);

        // Replace Socialite Instance with our mock
        $this->app->instance(Socialite::class, $stub);
    }
}
