<?php

namespace Tests\Feature\Meta;

use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class SubAppointmentTest extends TestCase
{
    /**
     * Test get all sub appointment cancellation vaults
     *
     * @return void
     */
    public function test_get_all_sub_appointment_cancellation_vaults()
    {
        $response = $this->getJson(
            '/api/meta/sub_appointment/all_cancellation_vaults'
        );
        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->where('1', 'Roofer');
            $json->where('2', 'Customer');
        });
    }

    /**
     * Test get all sub appointment statuses
     *
     * @return void
     */
    public function test_get_all_sub_appointment_statuses()
    {
        $response = $this->getJson('/api/meta/sub_appointment/all_statuses');
        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->where('1', 'Created');
            $json->where('2', 'In Process');
            $json->where('3', 'Processed');
            $json->where('4', 'Calculated');
            $json->where('5', 'Cancelled');
            $json->where('6', 'Draft');
        });
    }
}
