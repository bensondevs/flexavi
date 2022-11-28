<?php

namespace Tests\Unit\Repositories\Auths\AuthRepository;

use App\Models\Log\Log;
use App\Models\User\User;
use App\Repositories\Auths\AuthRepository;
use App\Traits\FeatureTestUsables;
use Tests\TestCase;

/**
 * @see \App\Repositories\Auths\AuthRepository::login()
 *      To the tested repository method.
 */
class LoginTest extends TestCase
{
    use FeatureTestUsables;

    /**
     * Ensure the owner can be authenticated properly.
     *
     * @test
     * @return void
     */
    public function it_authenticates_owner(): void
    {
        $user = $this->authenticateAsOwner();

        $return = app(AuthRepository::class)->login([
            'email' => $user->email,
            'password' => 'password',
        ]);
        $this->assertInstanceOf(User::class, $return);

        $this->assertEquals($user->id, $return->id);
        $this->assertTrue($user->hasCompany());
    }

    /**
     * Ensure the owner without company can be authenticated properly.
     *
     * @test
     * @return void
     */
    public function it_authenticates_owner_without_company(): void
    {
        $user = $this->authenticateAsOwner();
        $this->testCompany->forceDelete();

        $return = app(AuthRepository::class)->login([
            'email' => $user->email,
            'password' => 'password',
        ]);
        $this->assertInstanceOf(User::class, $return);

        $this->assertEquals($user->id, $return->id);
        $this->assertFalse($user->hasCompany());
    }

    /**
     * Ensure the employee authenticated properly.
     *
     * @test
     * @return void
     */
    public function it_authenticates_employee(): void
    {
        $user = $this->authenticateAsEmployee();

        $return = app(AuthRepository::class)->login([
            'email' => $user->email,
            'password' => 'password',
        ]);
        $this->assertInstanceOf(User::class, $return);

        $this->assertEquals($user->id, $return->id);
        $this->assertTrue($user->hasCompany());
    }

    /**
     * Ensure the employee without company properly.
     *
     * @test
     * @return void
     */
    public function it_rejects_employee_without_company(): void
    {
        $user = $this->authenticateAsEmployee();
        $this->testCompany->delete();

        $repository = app(AuthRepository::class);
        $return = $repository->login([
            'email' => $user->email,
            'password' => 'password',
        ]);
        $this->assertNull($return);

        $this->assertEquals(422, $repository->httpStatus);
        $this->assertEquals('error', $repository->status);
        $this->assertFalse($user->hasCompany());
    }

    /**
     * Ensure the logged-in user get update in last login time.
     *
     * @test
     * @return void
     */
    public function it_marks_last_login_time_of_user(): void
    {
        $user = $this->authenticateAsOwner();

        $return = app(AuthRepository::class)->login([
            'email' => $user->email,
            'password' => 'password',
        ]);
        $this->assertInstanceOf(User::class, $return);

        $this->assertNotNull($return->last_login_at);
    }

    /**
     * Ensure the logged-in user get token.
     *
     * @test
     * @return void
     */
    public function it_generates_token_for_authentication(): void
    {
        $user = $this->authenticateAsOwner();

        $return = app(AuthRepository::class)->login([
            'email' => $user->email,
            'password' => 'password',
        ]);
        $this->assertInstanceOf(User::class, $return);

        $this->assertNotNull($return->token);
    }

    /**
     * Ensure the method creates log.
     *
     * @test
     * @return void
     */
    public function it_creates_log_when_successful(): void
    {
        $user = $this->authenticateAsOwner();

        $return = app(AuthRepository::class)->login([
            'email' => $user->email,
            'password' => 'password',
        ]);
        $this->assertInstanceOf(User::class, $return);

        $logCreated = Log::whereCauserId($return->id)
            ->whereName('user.login')
            ->exists();
        $this->assertTrue($logCreated);
    }
}
