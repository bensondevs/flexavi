<?php

namespace Tests\Feature\Auth;

use App\Models\Company\Company;
use App\Models\User\User;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class LogoutTest extends TestCase
{
    /**
     * Test logout from system
     *
     * @return void
     */
    public function test_logout_from_system(): void
    {
        $company = Company::factory()->create();
        $user = User::factory()->employee($company)->create();

        $this->actingAs($user);

        $response = $this->postJson('/api/auth/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);
        $content = $response->getOriginalContent();
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $content['user']->token,
        ])->postJson('/api/auth/logout');
        $response->assertStatus(201);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('status');
            $json->has('message');
        });
    }
}
