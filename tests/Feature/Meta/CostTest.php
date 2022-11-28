<?php

namespace Tests\Feature\Meta;

use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class CostTest extends TestCase
{
    /**
     * Test get all cost costable types
     *
     * @return void
     */
    public function test_get_all_cost_costable_types()
    {
        $response = $this->getJson('/api/meta/cost/all_costable_types');
        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->where('1', 'Appointment');
            $json->where('2', 'Worklist');
            $json->where('3', 'Workday');
        });
    }
}
