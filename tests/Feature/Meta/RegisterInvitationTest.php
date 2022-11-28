<?php

namespace Tests\Feature\Meta;

use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class RegisterInvitationTest extends TestCase
{
    /**
     * Test get all register invitation statuses
     *
     * @return void
     */
    public function test_get_all_register_invitation_statuses()
    {
        $response = $this->getJson(
            '/api/meta/register_invitation/all_statuses'
        );
        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->where('1', 'Active');
            $json->where('2', 'Used');
            $json->where('3', 'Expired');
        });
    }
}
