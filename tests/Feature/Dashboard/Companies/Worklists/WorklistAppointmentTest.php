<?php

namespace Tests\Feature\Dashboard\Companies\Worklists;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;

use App\Models\{ 
    Company, 
    Owner, 
    Worklist, 
    Appointment, 
    Appointmentable, 
    User 
};

class WorklistAppointmentTest extends TestCase
{
    use DatabaseTransactions;

    private $baseUrl = '/api/dashboard/companies/worklists/appointments';

    /**
     * A view all worklist appointments test.
     *
     * @return void
     */
    public function test_view_all_worklist_appointments()
    {
        $company = Company::inRandomOrder()->first();
        $owner = Owner::factory()->for($company)->create();
        Sanctum::actingAs(($user = $owner->user), ['*']);

        $worklist =  Worklist::factory()->for($company)->create();
        $url = $this->baseUrl . '?worklist_id=' . $worklist->id;
        $response = $this->json('GET', $url);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('appointments');
            $json->has('appointments.data');
        });
    }

    /**
     * An attach appointment to worklist test.
     *
     * @return void
     */
    public function test_attach_appointment_to_worklist()
    {
        $company = Company::inRandomOrder()->first();
        $owner = Owner::factory()->for($company)->create();
        Sanctum::actingAs(($user = $owner->user), ['*']);

        $worklist = Worklist::factory()->for($company)->create();
        $appointment = Appointment::factory()->for($company)->create();

        $url = $this->baseUrl . '/attach';
        $response = $this->json('POST', $url, [
            'worklist_id' => $worklist->id,
            'appointment_id' => $appointment->id,
        ]);

        $response->assertStatus(201);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('message');
            $json->where('status', 'success');
        });
    }

    /**
     * An attach many appointments to worklist test.
     *
     * @return void
     */
    public function test_attach_many_appointments_to_worklist()
    {
        $company = Company::inRandomOrder()->first();
        $owner = Owner::factory()->for($company)->create();
        Sanctum::actingAs(($user = $owner->user), ['*']);

        $worklist = Worklist::factory()->for($company)->create();
        $appointmentables = Appointmentable::factory()
            ->for($company)
            ->count(5)
            ->create();

        $appointmentIds = [];
        foreach ($appointmentables as $appointmentable) {
            array_push($appointmentIds, $appointmentable->appointment_id);
        }
        $url = $this->baseUrl . '/attach_many';
        $response = $this->json('POST', $url, [
            'worklist_id' => $worklist->id,
            'appointment_ids' => $appointmentIds,
        ]);

        $response->assertStatus(201);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('message');
            $json->where('status', 'success');
        });
    }

    /**
     * A detach appointment from worklist test.
     *
     * @return void
     */
    public function test_detach_appointment_from_worklist()
    {
        $company = Company::inRandomOrder()->first();
        $owner = Owner::factory()->for($company)->create();
        Sanctum::actingAs(($user = $owner->user), ['*']);

        $worklist = Worklist::factory()
            ->for($company)
            ->create();
        $appointmentable = Appointmentable::factory()
            ->worklist($worklist)
            ->create();

        $url = $this->baseUrl . '/detach';
        $response = $this->json('POST', $url, [
            'worklist_id' => $worklist->id,
            'appointment_id' => $appointmentable->appointment_id,
        ]);

        $response->assertStatus(201);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('message');
            $json->where('status', 'success');
        });
    }

    /**
     * A detach many appointments from worklist test.
     *
     * @return void
     */
    public function test_detach_many_appointments_from_worklist()
    {
        $company = Company::inRandomOrder()->first();
        $owner = Owner::factory()->for($company)->create();
        Sanctum::actingAs(($user = $owner->user), ['*']);

        $worklist = Worklist::factory()->for($company)->create();

        $appointmentables = Appointmentable::factory()
            ->worklist($worklist)
            ->count(5)
            ->create();

        $appointmentIds = [];
        foreach ($appointmentables as $appointmentable) {
            array_push($appointmentIds, $appointmentable->appointment_id);
        }
        $url = $this->baseUrl . '/detach_many';
        $response = $this->json('POST', $url, [
            'worklist_id' => $worklist->id,
            'appointment_ids' => $appointmentIds,
        ]);

        $response->assertStatus(201);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('message');
            $json->where('status', 'success');
        });
    }

    /**
     * A truncate worklist appointments test.
     *
     * @return void
     */
    public function test_truncate_worklist_appointments()
    {
        $company = Company::inRandomOrder()->first();
        $owner = Owner::factory()->for($company)->create();
        Sanctum::actingAs(($user = $owner->user), ['*']);

        $worklist = Worklist::factory()->for($company)->create();
        $url = $this->baseUrl . '/truncate';
        $response = $this->json('POST', $url, ['worklist_id' => $worklist->id]);

        $response->assertStatus(201);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('message');
            $json->where('status', 'success');
        });
    }
}
