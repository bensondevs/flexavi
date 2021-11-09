<?php

namespace Tests\Feature\Dashboard\Companies;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;

use App\Models\{ 
    User, 
    Car, 
    CarRegisterTime, 
    Company,
    Worklist,
    Owner 
};

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
        $owner = Owner::factory()->for($company)->create();
        Sanctum::actingAs(($user = $owner->user), ['*']);

        $carRegisterTimes = CarRegisterTime::factory()->count(5);
        $car = Car::factory()
            ->has($carRegisterTimes, 'registeredTimes')
            ->create();
        $url = $this->baseUrl . '?car_id=' . $car->id;
        $response = $this->json('GET', $url);

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
        $owner = Owner::factory()->for($company)->create();
        Sanctum::actingAs(($user = $owner->user), ['*']);

        $car = Car::factory()->for($company)->create();

        $url = $this->baseUrl . '/register';
        $response = $this->json('POST', $url, [
            'car_id' => $car->id,

            'should_out_at' => now()->addMinutes(-3),
            'should_return_at' => now()->addMinutes(3),
        ]);

        $response->assertStatus(201);
        $response->assertJson(function (AssertableJson $json) {
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
        $owner = Owner::factory()->for($company)->create();
        Sanctum::actingAs(($user = $owner->user), ['*']);

        $url = $this->baseUrl . '/register_to_worklist';

        $car = Car::factory()->for($company)->create();
        $worklist = Worklist::factory()->for($company)->create();
        $response = $this->json('POST', $url, [
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
        $owner = Owner::factory()->for($company)->create();
        Sanctum::actingAs(($user = $owner->user), ['*']);

        $url = $this->baseUrl . '/mark_out';

        $time = CarRegisterTime::factory()->for($company)->create();
        $response = $this->json('POST', $url, [
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
        $owner = Owner::factory()->for($company)->create();
        Sanctum::actingAs(($user = $owner->user), ['*']);

        $url = $this->baseUrl . '/mark_return';

        $car = Car::factory()->for($company)->out()->create();

        $registerTime = CarRegisterTime::factory()
            ->for($company)
            ->for($car)
            ->create();
        $response = $this->json('POST', $url, [
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
        $owner = Owner::factory()->for($company)->create();
        Sanctum::actingAs(($user = $owner->user), ['*']);

        $carRegisterTime = CarRegisterTime::factory()->for($company)->create();
        
        $url = $this->baseUrl . '/update';
        $response = $this->json('PATCH', $url, [
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
    public function test_unregister_car_register_time()
    {
        $company = Company::inRandomOrder()->first();
        $owner = Owner::factory()->for($company)->create();
        Sanctum::actingAs(($user = $owner->user), ['*']);

        $time = CarRegisterTime::factory()->for($company)->create();

        $url = $this->baseUrl . '/unregister';
        $response = $this->json('DELETE', $url, [
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
