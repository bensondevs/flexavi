<?php

namespace Tests\Unit\Services\Google\AuthService;

use App\Services\Google\AuthService;
use Tests\TestCase;

/**
 * @see \App\Services\Google\AuthService
 *      To the tested service class.
 */
class AuthServiceTest extends TestCase
{
    /**
     * Service instance container property.
     *
     * @var AuthService|null
     */
    private ?AuthService $googleAuthService = null;

    /**
     * Test basic for making sure the class is not crashing.
     *
     * @test
     * @return void
     */
    public function it_does_not_have_stupid_error(): void
    {
        $this->assertInstanceOf(
            AuthService::class,
            $this->authService()
        );
    }

    /**
     * Create or get service instance
     *
     * @param bool $force
     * @return AuthService
     */
    protected function authService(bool $force = false): AuthService
    {
        if ($this->googleAuthService instanceof AuthService and !$force) {
            return $this->googleAuthService;
        }

        return $this->googleAuthService = app(AuthService::class);
    }
}
