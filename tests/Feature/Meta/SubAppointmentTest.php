<?php

namespace Tests\Feature\Meta;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SubAppointmentTest extends TestCase
{
    /**
     * Collect all cancellation vaults test.
     *
     * @return void
     */
    public function test_all_sub_appointment_cancellation_vaults()
    {
        $url = '/api/meta/sub_appointment/all_cancellation_vaults';
        $response = $this->json('GET', $url);

        $response->assertStatus(200);
    }

    /**
     * Collect all statuses test.
     *
     * @return void
     */
    public function test_all_sub_appointment_statuses()
    {
        $url = '/api/meta/sub_appointment/all_statuses';
        $response = $this->json('GET', $url);

        $response->assertStatus(200);
    }
}
