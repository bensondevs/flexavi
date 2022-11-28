<?php

namespace Tests\Feature\Meta;

use App\Models\User\User;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Meta\UserController
 *      To the controller
 */
class UserTest extends TestCase
{
    /**
     * Test user has company
     *
     * @return void
     * @see \App\Http\Controllers\Meta\UserController::hasCompany()
     *    To the controller method
     */
    public function test_user_has_company(): void
    {
        $userWithCompany = User::factory()->owner()->create();
        $userWithoutCompany = User::factory()->create();
        $userWithoutCompany->company->delete();

        //  check on user with company
        (function () use ($userWithCompany) {
            $response = $this->getJson(
                urlWithParams('/api/meta/user/has_company', [
                    'id' => $userWithCompany->id,
                ])
            );
            $content = json_decode($response->getContent());
            $hasCompany = $content->has_company;

            $this->assertTrue($hasCompany);
        })();

        //  check on user without company
        (function () use ($userWithoutCompany) {
            $response = $this->getJson(
                urlWithParams('/api/meta/user/has_company', [
                    'id' => $userWithoutCompany->id,
                ])
            );
            $content = json_decode($response->getContent());
            $hasCompany = $content->has_company;

            $this->assertFalse($hasCompany);
        })();
    }

    /**
     * Test check if email already used
     *
     * @return void
     * @see \App\Http\Controllers\Meta\UserController::checkEmailUsed()
     *   To the controller method
     */
    public function test_check_if_email_used(): void
    {
        $response = $this->getJson(urlWithParams('/api/meta/user/check_email_used', [
            'email' => 'test@mail.com'
        ]));
        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->whereType('status', 'string');
            $json->whereType('message', 'string');
        });
    }
}
