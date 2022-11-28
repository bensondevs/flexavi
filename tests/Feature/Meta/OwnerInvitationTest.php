<?php

namespace Tests\Feature\Meta;

use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class OwnerInvitationTest extends TestCase
{
    /**
     * Module base URL container constant.
     *
     * @const
     */
    public const MODULE_BASE_URL = '/api/meta/owner/invitation';

    /**
     * Test get all owner invitation statuses
     *
     * @return void
     */
    public function test_get_all_owner_invitation_statues(): void
    {
        $response = $this->getJson(self::MODULE_BASE_URL.'/statuses');
        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->where(1, 'Active');
            $json->where(2, 'Used');
            $json->where(3, 'Expired');
            $json->where(4, 'Cancelled');
        });
    }
}
