<?php

namespace Tests\Feature\Meta;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RegisterInvitationTest extends TestCase
{
    /**
     * An register invitation statuses test.
     *
     * @return void
     */
    public function test_all_register_invitation_statuses()
    {
        $url = '/api/meta/register_invitation/all_statuses';
        $response = $this->json('GET', $url);

        $response->assertStatus(200);
    }
}
