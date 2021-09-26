<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Models\User;

class ResetPasswordTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_reset_password()
    {
        if (! $user = User::whereHas('resetPasswordToken')->first()) {
            $user = User::whereDoesntHave('resetPasswordToken')->first();
            $user->generateResetPasswordToken();
        }

        $resetToken = $user->resetPasswordToken;
        $password = 'passwordExample123!';

        $url = '/api/auth/reset_password';
        $response = $this->withHeaders(['Accept' => 'application/json'])->post($url, [
            'reset_password_token' => $resetToken->token,
            'password' => $password,
            'confirm_password' => $password,
        ]);

        $response->assertStatus(201);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('message');
            $json->where('status.0', 'success');
            $json->where('status.1', 'success');
        });
    }
}
