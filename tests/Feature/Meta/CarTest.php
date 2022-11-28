<?php

namespace Tests\Feature\Meta;

use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class CarTest extends TestCase
{
    /**
     * Test get all car statuses
     *
     * @return void
     */
    public function test_get_all_car_statuses()
    {
        $response = $this->getJson('/api/meta/car/all_statuses');
        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->where('1', 'Free');
            $json->where('2', 'Out');
        });
    }
}
