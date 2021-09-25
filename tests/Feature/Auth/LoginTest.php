<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LoginTest extends TestCase
{
    /*use RefreshDatabase;*/
    
    /**
     * Test Success Login
     *
     * @return void
     */
    public function test_success_login()
    {
        $loginData = [
            'email' => 'owner1@flexavi.nl', 
            'password' => 'owner1',
        ];
        $response = $this->postJson('/api/auth/login', $loginData);

        $response->assertStatus(201);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('user')
                ->has('status')
                ->has('message');
        });
    }

    /**
     * Test Wrong password Login
     *
     * @return void
     */
    public function test_wrong_password_login()
    {
        $loginData = [
            'email' => 'owner1@flexavi.nl',
            'password' => 'thisIsMismatchPasswordTest',
        ];
        $response = $this->postJson('/api/auth/login', $loginData);

        $response->assertStatus(422);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('user');
            $json->whereType('user', 'null');

            $json->has('status');
            $json->whereType('status', 'string');
            $json->where('status', 'error');

            $json->has('message');
            $json->whereType('message', 'string');
            $json->where('message', 'Password mismatch the record!');
        });
    }

    /**
     * Test blank password Login
     *
     * @return void
     */
    public function test_blank_email_login()
    {
        $loginData = [
            'email' => null,
            'password' => 'owner1',
        ];
        $response = $this->postJson('/api/auth/login', $loginData);

        $response->assertStatus(422);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('message');
            $json->where('message', 'The given data was invalid.');

            $json->has('errors.email');
        });
    }

    /**
     * Test Wrong password Login
     *
     * @return void
     */
    public function test_blank_password_login()
    {
        $loginData = [
            'email' => 'owner1@flexavi.nl',
            'password' => null,
        ];
        $response = $this->postJson('/api/auth/login', $loginData);

        $response->assertStatus(422);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('message');
            $json->where('message', 'The given data was invalid.');

            $json->has('errors.password');
        });
    }
}
