<?php

namespace Tests\Feature\Dashboard\Company\Appointment;

use App\Models\User\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class AppointmentTest extends TestCase
{
    use WithFaker;

    /**
     * Test populate company appointments
     *
     * @return void
     *
     * @todo Hidden feature for next release
     * TODO: Hidden feature for next release
     *
public function test_populate_company_appointments()
    {
        $this->actingAs(
            $user = User::factory()
                ->owner()
                ->create()
        );
        $response = $this->getJson('/api/dashboard/companies/appointments');
        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('appointments');
            $json->whereType('appointments.data', 'array');

            // pagination meta
            $json->has('appointments.current_page');
            $json->has('appointments.first_page_url');
            $json->has('appointments.from');
            $json->has('appointments.last_page');
            $json->has('appointments.last_page_url');
            $json->has('appointments.links');
            $json->has('appointments.next_page_url');
            $json->has('appointments.path');
            $json->has('appointments.per_page');
            $json->has('appointments.prev_page_url');
            $json->has('appointments.to');
            $json->has('appointments.total');
        });
    }
     */


    /**
     * Test populate company trashed appointments
     *
     * @return void
     *
     * @todo Hidden feature for next release
     * TODO: Hidden feature for next release
     *
public function test_populate_company_trashed_appointments()
    {
        $this->actingAs(
            $user = User::factory()
                ->owner()
                ->create()
        );
        $response = $this->getJson(
            '/api/dashboard/companies/appointments/trasheds'
        );
        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('appointments');
            $json->whereType('appointments.data', 'array');

            // pagination meta
            $json->has('appointments.current_page');
            $json->has('appointments.first_page_url');
            $json->has('appointments.from');
            $json->has('appointments.last_page');
            $json->has('appointments.last_page_url');
            $json->has('appointments.links');
            $json->has('appointments.next_page_url');
            $json->has('appointments.path');
            $json->has('appointments.per_page');
            $json->has('appointments.prev_page_url');
            $json->has('appointments.to');
            $json->has('appointments.total');
        });
    }
     */


    /**
     * Test save appointment as draft
     *
     * @return void
     *
     * @todo Hidden feature for next release
     * TODO: Hidden feature for next release
     *
     public function test_save_appointment_as_draft()
    {
        $this->actingAs(
            $user = User::factory()
                ->owner()
                ->create()
        );

        $company = $user->owner->company;

        $customer = Customer::factory()->create();

        $start = now();

        $input = [
            'customer_id' =>  $customer->id,
            'start_date' => $start->toDateString(),
            'end_date' => $start->toDateString(),
            'start_time' => $start->toTimeString(),
            'end_time' => $start->toTimeString(),
            'related_appointment_ids' => null,
            'related_appointment_ids.*' => null,
            'include_weekend' => $this->faker->randomElement([true, false]),
            'type' => Type::getRandomValue(),
            'description' => null,
            'note' => null,
        ];

        $response = $this->postJson(
            '/api/dashboard/companies/appointments/draft',
            $input
        )->dump();

        $response->assertStatus(201);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('status');
            $json->has('message');

            $json->has('appointment');
        });
    }
     */
}
