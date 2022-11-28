<?php

namespace Tests\Feature\Auth;

use App\Enums\Auth\ResetPasswordType;
use App\Models\User\PasswordReset;
use App\Models\User\User;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class ForgotPasswordTest extends TestCase
{
    /**
     * Test search account by email
     *
     * @return void
     */
    public function test_search_account(): void
    {
        $user = User::factory()->create();
        $response = $this->postJson('/api/auth/password/find', [
            'email' => $user->email,
        ]);
        $response->assertSuccessful();

        $response->assertJson(function (AssertableJson $json) {
            $json->where('status', 'success');
            $json->has('message');
            $json->has('user');
        });
    }

    /**
     * Test send reset code
     *
     * @return void
     */
    public function test_send_reset_code_via_email(): void
    {
        $user = User::factory()->create();
        $response = $this->postJson('/api/auth/password/send_reset_code', [
            'user_id' => $user->id,
            'reset_via' => ResetPasswordType::Email
        ]);

        $response->assertSuccessful();

        $this->assertDatabaseHas((new PasswordReset())->getTable(), [
            'email' => $user->email,
            'reset_via' => ResetPasswordType::Email
        ]);

        $response->assertJson(function (AssertableJson $json) {
            $json->where('status', 'success');
            $json->has('message');
        });
    }

    /**
     * Test validate valid token
     *
     * @return void
     */
    public function test_validate_valid_token(): void
    {
        $resetPassword = PasswordReset::factory(['reset_via' => ResetPasswordType::Email])->create();
        $response = $this->postJson('/api/auth/password/validate_token', [
            'email' => $resetPassword->email,
            'token' => $resetPassword->token,
        ]);
        $response->assertSuccessful();

        $this->assertDatabaseHas((new PasswordReset())->getTable(), [
            'token' => $resetPassword->token
        ]);
        $response->assertJson(function (AssertableJson $json) {
            $json->where('status', 'success');
            $json->has('message');
            $json->has('password_reset');
        });
    }

    /**
     * Test validate invalid token
     *
     * @return void
     */
    public function test_validate_invalid_token(): void
    {
        $resetPassword = PasswordReset::factory(['reset_via' => ResetPasswordType::Email])->create();
        $response = $this->postJson('/api/auth/password/validate_token', [
            'email' => $resetPassword->email,
            'token' => $resetPassword->token . '1',
        ]);
        $response->assertStatus(422);
    }

    /**
     * Test valid reset password
     *
     * @return void
     */
    public function test_valid_reset_password(): void
    {
        $resetPassword = PasswordReset::factory(['reset_via' => ResetPasswordType::Email])->create();
        $response = $this->postJson('/api/auth/password/reset', [
            'reset_password_token' => $resetPassword->token,
            'password' => 'Password123#',
            'confirm_password' => 'Password123#',
        ]);

        $response->assertSuccessful();

        $response->assertJson(function (AssertableJson $json) {
            $json->where('status', 'success');
            $json->has('message');
        });
    }

    /**
     * Test reset password
     *
     * @return void
     */
    public function test_invalid_reset_password(): void
    {
        $resetPassword = PasswordReset::factory(['reset_via' => ResetPasswordType::Email])->create();
        $response = $this->postJson('/api/auth/password/reset', [
            'reset_password_token' => $resetPassword->token . '1',
            'password' => 'Password123#',
            'confirm_password' => 'Password123#',
        ]);
        $response->assertStatus(422);
    }
}
