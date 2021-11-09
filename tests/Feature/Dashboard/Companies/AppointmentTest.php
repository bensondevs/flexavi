<?php

namespace Tests\Feature\Dashboard\Companies;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;

use App\Models\{ 
    User, 
    Owner, 
    Customer, 
    Company, 
    Appointment, 
    Invoice 
};

use App\Enums\Appointment\AppointmentStatus;

class AppointmentTest extends TestCase
{
    use DatabaseTransactions;
    
    /**
     * A populate appointments test.
     *
     * @return void
     */
    public function test_view_all_appointments()
    {
        $company = Company::inRandomOrder()->first();
        $owner = Owner::factory()->for($company)->create();
        Sanctum::actingAs(($user = $owner->user), ['*']);

        $url = '/api/dashboard/companies/appointments';
        $response = $this->json('GET', $url);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('appointments');
            $json->has('appointments.data');
        });
    }

    /**
     * A populate customer appointments test.
     *
     * @return void
     */
    public function test_view_customer_appointments()
    {
        $company = Company::inRandomOrder()->first();
        $owner = Owner::factory()->for($company)->create();
        Sanctum::actingAs(($user = $owner->user), ['*']);

        $customer = Customer::factory()->for($company)->create();

        $url = '/api/dashboard/companies/appointments/of_customer?customer_id=' . $customer->id;
        $response = $this->json('GET', $url);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('appointments');
            $json->has('appointments.data');
        });
    }

    /**
     * A store appointment test.
     *
     * @return void
     */
    public function test_store_appointment()
    {
        $company = Company::inRandomOrder()->first();
        $owner = Owner::factory()->for($company)->create();
        Sanctum::actingAs(($user = $owner->user), ['*']);

        $customer = Customer::factory()->for($company)->create();

        $url = '/api/dashboard/companies/appointments/store';
        $response = $this->json('POST', $url, [
            'customer_id' => $customer->id,
            'start' => '2021-05-15',
            'end' => '2021-05-18',
            'include_weekend' => true,
            'type' => 1,
            'note' => 'Fixing leaking rooftop',
        ]);

        $response->assertStatus(201);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('status');
            $json->has('message');
        });
    }

    /**
     * An update appointment test.
     *
     * @return void
     */
    public function test_update_appointment()
    {
        $company = Company::inRandomOrder()->first();
        $owner = Owner::factory()->for($company)->create();
        Sanctum::actingAs(($user = $owner->user), ['*']);

        $url = '/api/dashboard/companies/appointments/update';
        $appointment = Appointment::factory()->for($company)->created()->create();
        $response = $this->json('PATCH', $url, [
            'id' => $appointment->id,
            'customer_id' => $appointment->customer_id,
            'start' => '2021-05-15',
            'end' => '2021-05-18',
            'include_weekend' => true,
            'type' => 1,
            'note' => 'Fixing leaking rooftop',
        ]);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->where('status', 'success');
            $json->has('message');
        });
    }

    /**
     * An execute appointmentstest.
     *
     * @return void
     */
    public function test_execute_appointment()
    {
        $company = Company::inRandomOrder()->first();
        $owner = Owner::factory()->for($company)->create();
        Sanctum::actingAs(($user = $owner->user), ['*']);

        $url = '/api/dashboard/companies/appointments/execute';
        $appointment = Appointment::factory()->for($company)->created()->create();
        $response = $this->json('POST', $url, [
            'appointment_id' => $appointment->id,
        ]);

        $response->assertStatus(201);
        $response->assertJson(function (AssertableJson $json) {
            $json->where('status', 'success');
            $json->has('message');
        });
    }

    /**
     * A process appointment test.
     *
     * @return void
     */
    public function test_process_appointment()
    {
        $company = Company::inRandomOrder()->first();
        $owner = Owner::factory()->for($company)->create();
        Sanctum::actingAs(($user = $owner->user), ['*']);

        $url = '/api/dashboard/companies/appointments/process';
        $appointment = Appointment::factory()->for($company)->inProcess()->create();
        $response = $this->json('POST', $url, [
            'appointment_id' => $appointment->id,
        ]);

        $response->assertStatus(201);
        $response->assertJson(function (AssertableJson $json) {
            $json->where('status', 'success');
            $json->has('message');
        });
    }

    /**
     * A cancel appointment test.
     *
     * @return void
     */
    public function test_cancel_appointment()
    {
        $company = Company::inRandomOrder()->first();
        $owner = Owner::factory()->for($company)->create();
        Sanctum::actingAs(($user = $owner->user), ['*']);

        $url = '/api/dashboard/companies/appointments/cancel';
        $appointment = Appointment::factory()->for($company)->created()->create();
        $response = $this->json('POST', $url, [
            'appointment_id' => $appointment->id,
            'cancellation_cause' => 'The rooder is terribly late',
            'cancellation_vault' => 1,
            'cancellation_note' => 'Roofer agreed to be arrived at 9, but he did\'t show up until 10. We try to make many calls but get no answer, what a dissapointment. He showed up at 11 and say the excuse about traffic jam and so on and so forth'
        ]);

        $response->assertStatus(201);
        $response->assertJson(function (AssertableJson $json) {
            $json->where('status', 'success');
            $json->has('message');
        });
    }

    /**
     * A reschedule appointment test.
     *
     * @return void
     */
    public function test_reschedule_appointment()
    {
        $company = Company::inRandomOrder()->first();
        $owner = Owner::factory()->for($company)->create();
        Sanctum::actingAs(($user = $owner->user), ['*']);

        $url = '/api/dashboard/companies/appointments/reschedule';
        $appointment = Appointment::factory()->for($company)->created()->create();
        $response = $this->json('POST', $url, [
            'appointment_id' => $appointment->id,
            'type' => $appointment->status,
            'start' => '2021-10-22',
            'end' => '2021-10-25',
        ]);

        $response->assertStatus(201);
        $response->assertJson(function (AssertableJson $json) {
            $json->where('status', 'success');
            $json->has('message');
        });
    }

    /**
     * A generate invoice from appointment test.
     *
     * @return void
     */
    public function test_generate_invoice_from_appointment()
    {
        $company = Company::inRandomOrder()->first();
        $owner = Owner::factory()->for($company)->create();
        Sanctum::actingAs(($user = $owner->user), ['*']);

        $url = '/api/dashboard/companies/appointments/generate_invoice';
        $appointment = Appointment::factory()->for($company)->calculated()->create();
        $response = $this->json('POST', $url, [
            'appointment_id' => $appointment->id,
            'payment_method' => 2,
        ]);

        $response->assertStatus(201);
        $response->assertJson(function (AssertableJson $json) {
            $json->where('status', 'success');
            $json->has('message');
            $json->has('invoice');
        });
    }

    /**
     * A generate invoice from appointment with invoice already test.
     *
     * @return void
     */
    public function test_generate_invoice_from_invoiced_appointment()
    {
        $company = Company::inRandomOrder()->first();
        $owner = Owner::factory()->for($company)->create();
        Sanctum::actingAs(($user = $owner->user), ['*']);

        $url = '/api/dashboard/companies/appointments/generate_invoice';
        $appointment = Appointment::factory()
            ->hasInvoice()
            ->for($company)
            ->create();
        $response = $this->json('POST', $url, [
            'appointment_id' => $appointment->id,
            'payment_method' => 2,
        ]);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->where('status', 'success');
            $json->has('message');
            $json->has('invoice');
            $json->has('invoice.appointment');
        });
    }

    /**
     * A delete appointment test.
     *
     * @return void
     */
    public function test_delete_appointment()
    {
        $company = Company::inRandomOrder()->first();
        $owner = Owner::factory()->for($company)->create();
        Sanctum::actingAs(($user = $owner->user), ['*']);

        $appointment = Appointment::factory()->for($company)->create();
        $url = '/api/dashboard/companies/appointments/delete';
        $response = $this->json('DELETE', $url, [
            'appointment_id' => $appointment->id,
        ]);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('message');
            $json->where('status', 'success');
        });
    }

    /**
     * A populate trashed appointments test.
     *
     * @return void
     */
    public function test_view_all_trashed_appointments()
    {
        $company = Company::inRandomOrder()->first();
        $owner = Owner::factory()->for($company)->create();
        Sanctum::actingAs(($user = $owner->user), ['*']);

        $url = '/api/dashboard/companies/appointments/trasheds';
        $response = $this->json('GET', $url);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('appointments.data');
        });
    }

    /**
     * A restore appointment test.
     *
     * @return void
     */
    public function test_restore_appointment()
    {
        $company = Company::inRandomOrder()->first();
        $owner = Owner::factory()->for($company)->create();
        Sanctum::actingAs(($user = $owner->user), ['*']);

        $url = '/api/dashboard/companies/appointments/restore';

        $appointment = Appointment::factory()
            ->softDeleted()
            ->for($company)
            ->create();
        $response = $this->json('PATCH', $url, ['appointment_id' => $appointment->id]);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->where('status', 'success');
            $json->has('message');
            $json->has('appointment');
        });
    }
}
