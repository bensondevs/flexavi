<?php

namespace Tests\Feature\Dashboard\Companies\Appointments;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;

use App\Models\{ 
    Company, 
    Owner, 
    Appointment, 
    SubAppointment 
};

use App\Enums\SubAppointment\{
    SubAppointmentStatus as Status,
    SubAppointmentCancellationVault as Vault
};

class SubAppointmentTest extends TestCase
{
    use DatabaseTransactions;

    private $baseUrl = '/api/dashboard/companies/appointments/subs';

    /**
     * A view all sub-appointment test.
     *
     * @return void
     */
    public function test_view_all_appointments()
    {
        $company = Company::inRandomOrder()->first();
        $owner = Owner::factory()->for($company)->create();
        Sanctum::actingAs(($user = $owner->user), ['*']);

        $appointment = Appointment::factory()
            ->has(SubAppointment::factory()->for($company)->count(3), 'subs')
            ->for($company)
            ->create();
        $url = $this->baseUrl . '?appointment_id=' . $appointment->id;
        $response = $this->get($url);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('sub_appointments');
        });
    }

    /**
     * A store sub-appointment test.
     *
     * @return void
     */
    public function test_store_sub_appointment()
    {
        $company = Company::inRandomOrder()->first();
        $owner = Owner::factory()->for($company)->create();
        Sanctum::actingAs(($user = $owner->user), ['*']);

        $appointment = Appointment::factory()->for($company)->create();
        $url = $this->baseUrl . '/store';
        $response = $this->post($url, [
            'appointment_id' => $appointment->id,
            'start' => carbon()->parse($appointment->start)->addDays(1),
            'end' => carbon()->parse($appointment->end)->addDays(-1),
        ]);

        $response->assertStatus(201);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('message');
            $json->where('status', 'success');
        });
    }

    /**
     * An update sub-appointment test.
     *
     * @return void
     */
    public function test_update_sub_appointment()
    {
        $company = Company::inRandomOrder()->first();
        $owner = Owner::factory()->for($company)->create();
        Sanctum::actingAs(($user = $owner->user), ['*']);

        $subAppointment = SubAppointment::factory()->for($company)->created()->create();
        $url = $this->baseUrl . '/update';
        $response = $this->patch($url, [
            'sub_appointment_id' => $subAppointment->id,
            'start' => carbon()->parse($subAppointment->appointment->start)->addDays(1),
            'end' => carbon()->parse($subAppointment->appointment->end)->addDays(-1),
        ]);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('message');
            $json->where('status', 'success');
        });
    }

    /**
     * An execute sub-appointment test.
     *
     * @return void
     */
    public function test_execute_sub_appointment()
    {
        $company = Company::inRandomOrder()->first();
        $owner = Owner::factory()->for($company)->create();
        Sanctum::actingAs(($user = $owner->user), ['*']);

        $subAppointment = SubAppointment::factory()
            ->for($company)
            ->created()
            ->create();
        $url = $this->baseUrl . '/execute';
        $response = $this->post($url, [
            'sub_appointment_id' => $subAppointment->id,
        ]);

        $response->assertStatus(201);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('message');
            $json->where('status', 'success');
        });
    }

    /**
     * A process sub-appointment test.
     *
     * @return void
     */
    public function test_process_sub_appointment()
    {
        $company = Company::inRandomOrder()->first();
        $owner = Owner::factory()->for($company)->create();
        Sanctum::actingAs(($user = $owner->user), ['*']);

        $subAppointment = SubAppointment::factory()
            ->for($company)
            ->inProcess()
            ->create();
        $url = $this->baseUrl . '/process';
        $response = $this->post($url, [
            'sub_appointment_id' => $subAppointment->id,
        ]);

        $response->assertStatus(201);
    }

    /**
     * A cancel sub-appointment test.
     *
     * @return void
     */
    public function test_cancel_sub_appointment()
    {
        $company = Company::inRandomOrder()->first();
        $owner = Owner::factory()->for($company)->create();
        Sanctum::actingAs(($user = $owner->user), ['*']);

        $subAppointment = SubAppointment::factory()
            ->for($company)
            ->inProcess()
            ->create();
        $url = $this->baseUrl . '/cancel';
        $response = $this->post($url, [
            'sub_appointment_id' => $subAppointment->id,
            'cancellation_cause' => 'Cause Example',
            'cancellation_reason' => 'Reason Example',
            'cancellation_vault' => rand(Vault::Roofer, Vault::Customer),
        ]);

        $response->assertStatus(201);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('message');
            $json->where('status', 'success');
        });
    }

    /**
     * A delete sub-appointment test.
     *
     * @return void
     */
    public function test_delete_sub_appointment()
    {
        $company = Company::inRandomOrder()->first();
        $owner = Owner::factory()->for($company)->create();
        Sanctum::actingAs(($user = $owner->user), ['*']);

        $subAppointment = SubAppointment::factory()
            ->for($company)
            ->create();
        $url = $this->baseUrl . '/delete';
        $response = $this->delete($url, ['sub_appointment_id' => $subAppointment->id]);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('message');
            $json->where('status', 'success');
        });
    }
}
