<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Models\User;

class ForgotPasswordTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * A forgot password feature.
     *
     * @return void
     */
    public function test_forgot_password()
    {
        $user = User::whereDoesntHave('resetPasswordToken')->first();
        
        $headers = ['Accept' => 'application/json'];
        $url = '/api/auth/forgot_password';

        $response = $this->withHeaders($headers)->post($url, [
            'email' => $user->email,
        ]);
        $response->assertStatus(201);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('message');
            $json->where('status', 'success');
        });
    }
}
