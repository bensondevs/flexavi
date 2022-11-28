<?php

namespace Tests\Feature\Meta;

use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class AppointmentTest extends TestCase
{
    /**
     * Test get all appointment cancellation vaults
     *
     * @return void
     */
    public function test_get_all_appointment_cancellation_vaults()
    {
        $response = $this->getJson(
            '/api/meta/appointment/all_cancellation_vaults'
        );
        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->where('1', 'Roofer');
            $json->where('2', 'Customer');
        });
    }

    /**
     * Test get all appointment statuses
     *
     * @return void
     */
    public function test_get_all_appointment_statuses()
    {
        $response = $this->getJson('/api/meta/appointment/all_statuses');
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

    /**
     * Test get all appointment types
     *
     * @return void
     */
    public function test_get_all_appointment_types()
    {
        $response = $this->getJson('/api/meta/appointment/all_types');
        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->where('1', 'Inspection');
            $json->where('2', 'Quotation');
            $json->where('3', 'Execute Work');
            $json->where('4', 'Warranty');
            $json->where('5', 'Payment Pick-Up');
            $json->where('6', 'Payment Reminder');
        });
    }
}
