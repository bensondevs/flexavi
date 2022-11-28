<?php

namespace Tests\Feature\Auth\Login;

use App\Models\User\User;
use App\Models\User\UserSocialiteAccount;
use Tests\Shared\MockSocialite;
use Tests\TestCase;

class GoogleLoginTest extends TestCase
{
    use MockSocialite;

    /**
     * Test it route redirect to google
     * @test
     * @return void
     */
    public function it_redirect_to_google(): void
    {
        $this
            ->get('/api/auth/socialite/login/google/redirect')
            ->assertStatus(200)
            ->assertSee('accounts.google.com\/o\/oauth2\/auth');
    }

    public function test_login_with_google(): void
    {
        $user = User::factory()->owner()->create([
            'email' => random_string(12) . '@exclolab.com',
        ]);

        $provider = UserSocialiteAccount::factory()->for($user)->google()->create([
            'vendor_user_id' => rand(100000, 999999)
        ]);

        $this->mockSocialite(123123, 'rizal@exclolab.com', 'password');

        $response = $this->getJson('/api/auth/socialite/login/google/callback');
        $response->assertSuccessful();
        $response->assertJsonStructure([
            'message',
            'status',
            'data' => [
                'user' => [
                    "id",
                    "token",
                    'permissions',
                ]
            ]
        ]);
    }
}
