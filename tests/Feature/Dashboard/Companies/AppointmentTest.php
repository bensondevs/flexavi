<?php

namespace Tests\Feature\Dashboard\Companies;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Models\User;
use App\Models\Owner;
use App\Models\Customer;
use App\Models\Appointment;

use App\Enums\Appointment\AppointmentStatus;

class AppointmentTest extends TestCase
{
    /**
     * A populate appointments test.
     *
     * @return void
     */
    public function test_view_all_appointments()
    {
        $owner = Owner::whereHas('user')->first();
        $user = $owner->user;
        $token = $user->generateToken();

        $headers = [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ];
        $url = '/api/dashboard/companies/appointments';
        $response = $this->withHeaders($headers)->get($url);

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
        $owner = Owner::whereHas('user')->first();
        $user = $owner->user;
        $token = $user->generateToken();

        $customer = Customer::where('company_id', $owner->company_id)->first();

        $headers = [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ];
        $url = '/api/dashboard/companies/appointments/of_customer?customer_id=' . $customer->id;
        $response = $this->withHeaders($headers)->get($url);

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
        $owner = Owner::whereHas('user')->first();
        $user = $owner->user;
        $token = $user->generateToken();

        $customer = Customer::where('company_id', $owner->company_id)->first();

        $headers = [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ];
        $url = '/api/dashboard/companies/appointments/store';
        $response = $this->withHeaders($headers)->post($url, [
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
        $owner = Owner::whereHas('user')->first();
        $user = $owner->user;
        $token = $user->generateToken();

        $headers = [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ];
        $url = '/api/dashboard/companies/appointments/update';
        $appointment = Appointment::where('company_id', $owner->company_id)
            ->where('status', AppointmentStatus::Created)
            ->first();
        $response = $this->withHeaders($headers)->patch($url, [
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
        $owner = Owner::whereHas('user')->first();
        $user = $owner->user;
        $token = $user->generateToken();

        $headers = [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ];
        $url = '/api/dashboard/companies/appointments/execute';
        $appointment = Appointment::where('company_id', $owner->company_id)
            ->where('status', AppointmentStatus::Created)
            ->first();
        $response = $this->withHeaders($headers)->post($url, [
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
        $owner = Owner::whereHas('user')->first();
        $user = $owner->user;
        $token = $user->generateToken();

        $headers = [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ];
        $url = '/api/dashboard/companies/appointments/process';
        $appointment = Appointment::where('company_id', $owner->company_id)
            ->where('status', AppointmentStatus::InProcess)
            ->first();
        $response = $this->withHeaders($headers)->post($url, [
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
        $owner = Owner::whereHas('user')->first();
        $user = $owner->user;
        $token = $user->generateToken();

        $headers = [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ];
        $url = '/api/dashboard/companies/appointments/cancel';
        $appointment = Appointment::where('company_id', $owner->company_id)
            ->where('status', AppointmentStatus::Created)
            ->first();
        $response = $this->withHeaders($headers)->post($url, [
            'appointment_id' => $appointment->id,
            'cancellation_cause' => 'The rooder is terribly late',
            'cancellation_vault' => 1,
            'cancellation_note' => 'oofer agreed to be arrived at 9, but he did\'t show up until 10. We try to make many calls but get no answer, what a dissapointment. He showed up at 11 and say the excuse about traffic jam and so on and so forth'
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
        $owner = Owner::whereHas('user')->first();
        $user = $owner->user;
        $token = $user->generateToken();

        $headers = [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ];
        $url = '/api/dashboard/companies/appointments/reschedule';
        $appointment = Appointment::where('company_id', $owner->company_id)
            ->where('status', '!=', AppointmentStatus::Calculated)
            ->first();
        $response = $this->withHeaders($headers)->post($url, [
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
        $owner = Owner::whereHas('user')->first();
        $user = $owner->user;
        $token = $user->generateToken();

        $headers = [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ];
        $url = '/api/dashboard/companies/appointments/generate_invoice';
        $appointment = Appointment::where('company_id', $owner->company_id)
            ->whereDoesntHave('invoice')
            ->first();
        $response = $this->withHeaders($headers)->post($url, [
            'appointment_id' => $appointment->id,
            'payment_method' => 2,
        ]);

        $response->assertStatus(201);
        $response->assertJson(function (AssertableJson $json) {
            $json->where('status', 'success');
            $json->has('message');
            $json->has('invoice');
            $json->has('invoice.appointment');
        });
    }

    /**
     * A generate invoice from appointment with invoice already test.
     *
     * @return void
     */
    public function test_generate_invoice_from_invoiced_appointment()
    {
        $owner = Owner::whereHas('user')->first();
        $user = $owner->user;
        $token = $user->generateToken();

        $headers = [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ];
        $url = '/api/dashboard/companies/appointments/generate_invoice';
        $appointment = Appointment::where('company_id', $owner->company_id)
            ->whereHas('invoice')
            ->first();
        $response = $this->withHeaders($headers)->post($url, [
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
        $owner = Owner::whereHas('user')->first();
        $user = $owner->user;
        $token = $user->generateToken();

        $headers = [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ];
        $appointment = Appointment::where('company_id', $owner->company_id)->first();
        $url = '/api/dashboard/companies/appointments/delete';
        $response = $this->withHeaders($headers)->delete($url, [
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
        $owner = Owner::whereHas('user')->first();
        $user = $owner->user;
        $token = $user->generateToken();

        $headers = [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ];
        $url = '/api/dashboard/companies/appointments/trasheds';
        $response = $this->withHeaders($headers)->get($url);

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
        $owner = Owner::whereHas('user')->first();
        $user = $owner->user;
        $token = $user->generateToken();

        $headers = [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ];
        $url = '/api/dashboard/companies/appointments/restore';

        $appointment = Appointment::onlyTrashed()
            ->where('company_id', $owner->company_id)
            ->first();
        if (! $appointment) {
            $appointment = Appointment::where('company_id', $owner->company_id)
                ->first();
            $id = $appointment->id;
            $appointment->delete();

            $appointment = Appointment::onlyTrashed()->findOrFail($id);
        }
        $response = $this->withHeaders($headers)->patch($url, [
            'appointment_id' => $appointment->id,
        ]);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->where('status', 'success');
            $json->has('message');
            $json->has('appointment');
        });
    }
}
