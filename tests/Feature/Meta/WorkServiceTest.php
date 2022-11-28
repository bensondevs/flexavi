<?php

namespace Tests\Feature\Meta;

use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class WorkServiceTest extends TestCase
{
    /**
     * Test get all car statuses
     *
     * @return void
     */
    public function test_get_all_work_service_statuses(): void
    {
        $response = $this->getJson('/api/meta/work_service/all_statuses');
        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->where('0', 'Inactive');
            $json->where('1', 'Active');
        });
    }
}
