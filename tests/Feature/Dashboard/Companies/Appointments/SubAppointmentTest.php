<?php

namespace Tests\Feature\Dashboard\Companies\Appointments;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Models\{ Company, Appointment, SubAppointment };

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
        do {
            $company = Company::inRandomOrder()->first();
            $owner = $company->owners()->first();
            $user = $owner->user;
        } while (! $user);
        $token = $user->generateToken();

        $headers = [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ];

        $appointment = $company->appointments()
            ->whereHas('subs')
            ->first();
        $url = $this->baseUrl . '?appointment_id=' . $appointment->id;
        $response = $this->withHeaders($headers)->get($url);

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
        do {
            $company = Company::inRandomOrder()->first();
            $owner = $company->owners()->first();
            $user = $owner->user;
        } while (! $user);
        $token = $user->generateToken();

        $headers = [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ];

        $appointment = $company->appointments()
            ->inRandomOrder()
            ->first();
        $url = $this->baseUrl . '/store';
        $response = $this->withHeaders($headers)->post($url, [
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
        do {
            $company = Company::inRandomOrder()->first();
            $owner = $company->owners()->first();
            $user = $owner->user;
        } while (! $user);
        $token = $user->generateToken();

        $headers = [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
            'Content-Type' => 'application/x-www-form-urlencoded',
        ];

        $subAppointment = $company->subAppointments()
            ->where('status', Status::Created)
            ->with('appointment')
            ->first();
        $url = $this->baseUrl . '/update';
        $response = $this->withHeaders($headers)->patch($url, [
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
        do {
            $company = Company::inRandomOrder()->first();
            $owner = $company->owners()->first();
            $user = $owner->user;
        } while (! $user);
        $token = $user->generateToken();

        $headers = [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ];

        $subAppointment = $company->subAppointments()
            ->where('status', Status::Created)
            ->inRandomOrder()
            ->first();
        $url = $this->baseUrl . '/execute';
        $response = $this->withHeaders($headers)->post($url, [
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
        do {
            $company = Company::inRandomOrder()->first();
            $owner = $company->owners()->first();
            $user = $owner->user;
        } while (! $user);
        $token = $user->generateToken();

        $headers = [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ];

        $subAppointment = $company->subAppointments()
            ->where('status', Status::InProcess)
            ->inRandomOrder()
            ->first();
        $url = $this->baseUrl . '/process';
        $response = $this->withHeaders($headers)->post($url, [
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
        do {
            $company = Company::inRandomOrder()->first();
            $owner = $company->owners()->first();
            $user = $owner->user;
        } while (! $user);
        $token = $user->generateToken();

        $headers = [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ];

        $subAppointment = $company->subAppointments()
            ->where('status', '!=', Status::Processed)
            ->where('status', '!=', Status::Cancelled)
            ->inRandomOrder()
            ->first();
        $url = $this->baseUrl . '/cancel';
        $response = $this->withHeaders($headers)->post($url, [
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
        do {
            $company = Company::inRandomOrder()->first();
            $owner = $company->owners()->first();
            $user = $owner->user;
        } while (! $user);
        $token = $user->generateToken();

        $headers = [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ];

        $subAppointment = $company->subAppointments()
            ->inRandomOrder()
            ->first();
        $url = $this->baseUrl . '/delete';
        $response = $this->withHeaders($headers)->delete($url, [
            'sub_appointment_id' => $subAppointment->id,
        ]);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('message');
            $json->where('status', 'success');
        });
    }
}
