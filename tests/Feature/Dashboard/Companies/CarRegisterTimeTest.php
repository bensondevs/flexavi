<?php

namespace Tests\Feature\Dashboard\Companies;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;

use App\Models\{ User, Car, CarRegisterTime, Company, Owner };

class CarRegisterTimeTest extends TestCase
{
    use DatabaseTransactions;

    private $baseUrl = '/api/dashboard/companies/cars/register_times';

    /**
     * A populate car register times test.
     *
     * @return void
     */
    public function test_view_register_times()
    {
        $company = Company::inRandomOrder()->first();
        $owner = $company->owners()->inRandomOrder()->first() ?:
            Owner::factory()->create(['company_id' => $company->id]);
        Sanctum::actingAs(($user = $owner->user), ['*']);

        $car = $company->cars()->inRandomOrder()->first();
        $url = $this->baseUrl . '?car_id=' . $car->id;
        $response = $this->get($url);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('car');
            $json->has('car_register_times');
        });
    }

    /**
     * A register car time test.
     *
     * @return void
     */
    public function test_register_car_time()
    {
        $company = Company::inRandomOrder()->first();
        $owner = $company->owners()->inRandomOrder()->first() ?:
            Owner::factory()->create(['company_id' => $company->id]);
        Sanctum::actingAs(($user = $owner->user), ['*']);

        $url = $this->baseUrl . '/register';

        $car = $company->cars()->inRandomOrder()->first();
        $out = now()->addMinutes(-3);
        $return = now()->addMinutes(3);
        $response = $this->post($url, [
            'car_id' => $car->id,

            'should_out_at' => $out,
            'should_return_at' => $return,
        ]);

        $response->assertStatus(201);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('time');
            $json->has('message');
            $json->where('status', 'success');
        });
    }

    /**
     * A register car time according to worklist test.
     *
     * @return void
     */
    public function test_register_car_to_worklist()
    {
        $company = Company::inRandomOrder()->first();
        $owner = $company->owners()->inRandomOrder()->first() ?:
            Owner::factory()->create(['company_id' => $company->id]);
        Sanctum::actingAs(($user = $owner->user), ['*']);

        $url = $this->baseUrl . '/register_to_worklist';

        $car = $company->cars()->inRandomOrder()->first() ?:
            Car::factory()->create(['company_id' => $company->id]);
        $worklist = $company->worklists()->inRandomOrder()->first() ?:
            Worklist::factory()->create(['company_id' => $company->id]);
        $response = $this->post($url, [
            'car_id' => $car->id,
            'worklist_id' => $worklist->id,
        ]);

        $response->assertStatus(201);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('message');
            $json->where('status', 'success');
        });
    }

    /**
     * A mark car as out test.
     *
     * @return void
     */
    public function test_mark_car_out()
    {
        $company = Company::inRandomOrder()->first();
        $owner = $company->owners()->inRandomOrder()->first() ?:
            Owner::factory()->create(['company_id' => $company->id]);
        Sanctum::actingAs(($user = $owner->user), ['*']);

        $url = $this->baseUrl . '/mark_out';

        $time = $company->carRegisterTimes()
            ->inRandomOrder()
            ->first() ?: CarRegisterTime::factory()->create(['company_id' => $company->id]);
        $response = $this->post($url, [
            'car_register_time_id' => $time->id,
        ]);

        $response->assertStatus(201);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('message');
            $json->where('status', 'success');
        });
    }

    /**
     * A mark car as returned test.
     *
     * @return void
     */
    public function test_mark_car_return()
    {
        $company = Company::inRandomOrder()->first();
        $owner = $company->owners()->inRandomOrder()->first() ?:
            Owner::factory()->create(['company_id' => $company->id]);
        Sanctum::actingAs(($user = $owner->user), ['*']);

        $url = $this->baseUrl . '/mark_return';

        $car = $company->cars()->inRandomOrder()->first() ?:
            Car::factory()->create(['company_id' => $company->id]);
        $registerTime = $car->currentRegisteredTime ?: 
            CarRegisterTime::factory()->create([
                'car_id' => $car->id,
                'company_id' => $car->company_id,
            ]);
        $response = $this->post($url, [
            'car_register_time_id' => $registerTime->id,
        ]);

        $response->assertStatus(201);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('message');
            $json->where('status', 'success');
        });
    }

    /**
     * An update car register time test.
     *
     * @return void
     */
    public function test_update_car_register_time()
    {
        $company = Company::inRandomOrder()->first();
        $owner = $company->owners()->inRandomOrder()->first() ?:
            Owner::factory()->create(['company_id' => $company->id]);
        Sanctum::actingAs(($user = $owner->user), ['*']);

        $url = $this->baseUrl . '/update';
        $carRegisterTime = $company->carRegisterTimes()->inRandomOrder()->first() ?:
            CarRegisterTime::factory()->create(['company_id' => $company->id]);
        $response = $this->patch($url, [
            'id' => $carRegisterTime->id,
            'should_out_at' => now(),
            'should_return_at' => now()->addHours(2),
        ]);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('message');
            $json->where('status', 'success');
        });
    }

    /**
     * A delete car register time test.
     *
     * @return void
     */
    public function test_delete_car_register_time()
    {
        $company = Company::inRandomOrder()->first();
        $owner = $company->owners()->inRandomOrder()->first() ?:
            Owner::factory()->create(['company_id' => $company->id]);
        Sanctum::actingAs(($user = $owner->user), ['*']);

        $url = $this->baseUrl . '/delete';
        $time = $company->carRegisterTimes()->first() ?:
            CarRegisterTime::factory()->create(['company_id' => $company->id]);
        $response = $this->delete($url, [
            'car_register_time_id' => $time->id,
            'force' => true,
        ]);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('message');
            $json->where('status', 'success');
        });
    }
}
