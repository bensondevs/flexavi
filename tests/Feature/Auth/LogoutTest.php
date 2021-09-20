<?php

namespace Tests\Feature\Auth;

use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LogoutTest extends TestCase
{
    /**
     * A logout test.
     *
     * @return void
     */
    public function test_success_logout()
    {
        $loginData = [
            'email' => 'owner1@flexavi.nl',
            'password' => 'owner1',
        ];
        $response = $this->postJson('/api/auth/login', $loginData);
        $content = $response->getOriginalContent();
        $user = $content['user'];
        $token = $user->token;
        
        $headers = [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ];
        $url = '/api/auth/logout';
        $response = $this->withHeaders($headers)->post($url, []);
        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('status');
            $json->has('message');
        });
    }
}
