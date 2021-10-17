<?php

namespace Tests\Feature\Dashboard\Companies\Worklists;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Models\{
    Company, Worklist, Appointment
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
        $owner = $company->owners()->inRandomOrder()->first();
        $user = $owner->user;
        $token = $user->generateToken();

        $headers = [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ];

        $worklist = $company->worklists()
            ->inRandomOrder()
            ->first();
        $url = $this->baseUrl . '?worklist_id=' . $worklist->id;
        $response = $this->withHeaders($headers)->get($url);

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
        $owner = $company->owners()->inRandomOrder()->first();
        $user = $owner->user;
        $token = $user->generateToken();

        $headers = [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ];

        $worklist = $company->worklists()->inRandomOrder()->first();
        $appointment = $company->appointments()->inRandomOrder()->first();
        $url = $this->baseUrl . '/attach';
        $response = $this->withHeaders($headers)->post($url, [
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
        $owner = $company->owners()->inRandomOrder()->first();
        $user = $owner->user;
        $token = $user->generateToken();

        $headers = [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ];
        $worklist = $company->worklists()->inRandomOrder()->first();
        $appointments = $company->appointments()
            ->inRandomOrder()
            ->take(rand(3, 10))
            ->get();
        $appointmentIds = [];
        foreach ($appointments as $appointment) {
            array_push($appointmentIds, $appointment->id);
        }
        $url = $this->baseUrl . '/attach_many';
        $response = $this->withHeaders($headers)->post($url, [
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
        $owner = $company->owners()->inRandomOrder()->first();
        $user = $owner->user;
        $token = $user->generateToken();

        $headers = [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ];
        $worklist = $company->worklists()
            ->whereHas('appointments')
            ->inRandomOrder()
            ->first();
        $appointment = $worklist->appointments()->inRandomOrder()->first();
        $url = $this->baseUrl . '/detach';
        $response = $this->withHeaders($headers)->post($url, [
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
     * A detach many appointments from worklist test.
     *
     * @return void
     */
    public function test_detach_many_appointments_from_worklist()
    {
        $company = Company::inRandomOrder()->whereHas('worklists')->first();
        $owner = $company->owners()->inRandomOrder()->first();
        $user = $owner->user;
        $token = $user->generateToken();

        $headers = [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ];
        $worklist = $company->worklists()->inRandomOrder()->first();
        $appointments = $worklist->appointments()
            ->inRandomOrder()
            ->take(rand(1, $worklist->appointments()->count() - 1))
            ->get();
        $appointmentIds = [];
        foreach ($appointments as $appointment) {
            array_push($appointmentIds, $appointment->id);
        }
        $url = $this->baseUrl . '/detach_many';
        $response = $this->withHeaders($headers)->post($url, [
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
        $owner = $company->owners()->inRandomOrder()->first();
        $user = $owner->user;
        $token = $user->generateToken();

        $headers = [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ];
        $worklist = $company->worklists()->inRandomOrder()->first();
        $url = $this->baseUrl . '/truncate';
        $response = $this->withHeaders($headers)->post($url, [
            'worklist_id' => $worklist->id,
        ]);

        $response->assertStatus(201);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('message');
            $json->where('status', 'success');
        });
    }
}
