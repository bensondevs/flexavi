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
    Workday, 
    Worklist, 
    Appointment, 
    Owner, 
    Cost, 
    Costable 
};

class AppointmentCostTest extends TestCase
{
    use DatabaseTransactions;

    private $baseUrl = '/api/dashboard/companies/appointments/costs';

    /**
     * A view all appointment costs test.
     *
     * @return void
     */
    public function test_view_all_appointment_costs()
    {
        $company = Company::inRandomOrder()->first();
        $owner = Owner::factory()->for($company)->create();
        Sanctum::actingAs(($user = $owner->user), ['*']);

        $appointment = Appointment::factory()
            ->for($company)
            ->create();

        $url = $this->baseUrl . '?appointment_id=' . $appointment->id;
        $response = $this->json('GET', $url);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('costs');
        });
    }

    /**
     * A store cost and record to appointment test.
     *
     * @return void
     */
    public function test_store_cost_and_record_to_appointment()
    {
        $company = Company::inRandomOrder()->first();
        $owner = Owner::factory()->for($company)->create();
        Sanctum::actingAs(($user = $owner->user), ['*']);

        $appointment = Appointment::factory()->for($company)->create();
        $url = $this->baseUrl . '/store_record';
        $response = $this->json('POST', $url, [
            'cost_name' => 'Appointment Cost Name Example',
            'amount' => 9000,
            'paid_amount' => 1000,
            'appointment_id' => $appointment->id,
        ]);

        $response->assertStatus(201);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('status');
            $json->where('status.0', 'success');
            $json->where('status.1', 'success');

            $json->has('message');
        });
    }

    /**
     * A record cost to appointment test.
     *
     * @return void
     */
    public function test_record_cost_to_appointment()
    {
        $company = Company::inRandomOrder()->first();
        $owner = Owner::factory()->for($company)->create();
        Sanctum::actingAs(($user = $owner->user), ['*']);

        $appointment = Appointment::factory()->for($company)->create();
        $cost = $company->costs()->inRandomOrder()->first();
        $url = $this->baseUrl . '/record';
        $response = $this->json('POST', $url, [
            'appointment_id' => $appointment->id,
            'cost_id' => $cost->id,
        ]);

        $response->assertStatus(201);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('message');
            $json->where('status', 'success');
        });
    }

    /**
     * A unrecord cost from appointment test.
     *
     * @return void
     */
    public function test_unrecord_cost_from_appointment()
    {
        $company = Company::inRandomOrder()->first();
        $owner = Owner::factory()->for($company)->create();
        Sanctum::actingAs(($user = $owner->user), ['*']);

        $appointment = Appointment::factory()->for($company)->create();
        $costable = Costable::factory()->appointment($appointment)->create();
        
        $url = $this->baseUrl . '/unrecord';
        $response = $this->json('POST', $url, [
            'appointment_id' => $appointment->id,
            'cost_id' => $costable->cost_id,
        ]);

        $response->assertStatus(201);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('message');
            $json->where('status', 'success');
        });
    }

    /**
     * A record many costs to appointment test.
     *
     * @return void
     */
    public function test_record_many_costs_to_appointment()
    {
        $company = Company::inRandomOrder()->first();
        $owner = Owner::factory()->create(['company_id' => $company->id]);
        Sanctum::actingAs(($user = $owner->user), ['*']);

        $appointment = Appointment::factory()->create(['company_id' => $company->id]);
        $costs = $company->costs()->inRandomOrder()->take(rand(4, 20))->get();
        $costIds = [];
        foreach ($costs as $cost) {
            array_push($costIds, $cost->id);
        }
        $url = $this->baseUrl . '/record_many';
        $response = $this->post($url, [
            'appointment_id' => $appointment->id,
            'cost_ids' => $costIds,
        ]);

        $response->assertStatus(201);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('message');
            $json->where('status', 'success');
        });
    }

    /**
     * A unrecord many costs from appointment test.
     *
     * @return void
     */
    public function test_unrecord_many_costs_from_appointment()
    {
        $company = Company::inRandomOrder()->first();
        $owner = Owner::factory()->create(['company_id' => $company->id]);
        Sanctum::actingAs(($user = $owner->user), ['*']);

        do {
            $appointment = $company->appointments()
                ->inRandomOrder()
                ->first();
        } while ($appointment->costs()->count() < 2);

        $unrecordedCostIds = [];
        foreach ($appointment->costs as $index => $cost) {
            if ($index && rand(0, 1)) {
                break;
            }

            array_push($unrecordedCostIds, $cost->id);
        }

        $url = $this->baseUrl . '/unrecord_many';
        $response = $this->post($url, [
            'appointment_id' => $appointment->id,
            'cost_ids' => $unrecordedCostIds,
        ]);

        $response->assertStatus(201);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('message');
            $json->where('status', 'success');
        });
    }

    /**
     * A truncate costs from appointment test.
     *
     * @return void
     */
    public function test_truncate_cost_from_appointment()
    {
        $company = Company::inRandomOrder()->first();
        $owner = Owner::factory()->create(['company_id' => $company->id]);
        Sanctum::actingAs(($user = $owner->user), ['*']);

        $appointment = $company->appointments()->inRandomOrder()->first();
        $url = $this->baseUrl . '/truncate';
        $response = $this->post($url, [
            'appointment_id' => $appointment->id,
        ]);

        $response->assertStatus(201);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('message');
            $json->where('status', 'success');
        });
    }
}
