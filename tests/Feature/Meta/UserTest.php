<?php

namespace Tests\Feature\Meta;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserTest extends TestCase
{
    /**
     * A check email used test.
     *
     * @return void
     */
    public function test_check_email_used()
    {
        $url = '/api/meta/user/check_email_used?email=test@email.com';
        $response = $this->json('GET', $url);

        $response->assertStatus(200);
    }

    /**
     * User ID Card Types test.
     *
     * @return void
     */
    public function test_all_user_id_card_types()
    {
        $url = '/api/meta/user/all_id_card_types';
        $response = $this->json('GET', $url);

        $response->assertStatus(200);
    }
}
