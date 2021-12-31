<?php

namespace Tests\Feature\Meta;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class AppointmentTest extends TestCase
{
    /**
     * Collect all cancellation vaults test.
     *
     * @return void
     */
    public function test_all_appointment_cancellation_vaults()
    {
        $url = '/api/meta/appointment/all_cancellation_vaults';
        $response = $this->json('GET', $url);

        $response->assertStatus(200);
    }

    /**
     * Collect all statuses test.
     *
     * @return void
     */
    public function test_all_appointment_statuses()
    {
        $url = '/api/meta/appointment/all_statuses';
        $response = $this->json('GET', $url);

        $response->assertStatus(200);
    }

    /**
     * Collect all types test.
     *
     * @return void
     */
    public function test_all_appointment_types()
    {
        $url = '/api/meta/appointment/all_types';
        $response = $this->json('GET', $url);

        $response->assertStatus(200);
    }
}
