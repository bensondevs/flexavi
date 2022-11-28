<?php

namespace Tests\Feature\Dashboard\Company\Fleet;

use Tests\TestCase;

class CarRegisterTimeTest extends TestCase
{
    /**
     * Test populate company car register times
     *
     * @return void
     * @todo Hidden feature for next release
     * TODO: Hidden feature for next release
     */
//    public function test_populate_company_car_register_times()
//    {
//        $this->actingAs(
//            $user = User::factory()
//                ->owner()
//                ->create()
//        );
//        $car = Car::factory()->create();
//        $response = $this->getJson(
//            '/api/dashboard/companies/cars/register_times?car_id=' . $car->id
//        );
//        $response->assertStatus(200);
//        $response->assertJson(function (AssertableJson $json) {
//            $json->has('car');
//            $json->has('car.id');
//            $json->has('car_register_times');
//            $json->whereType('car_register_times.data', 'array');
//
//            // pagination meta
//            $json->has('car_register_times.current_page');
//            $json->has('car_register_times.first_page_url');
//            $json->has('car_register_times.from');
//            $json->has('car_register_times.last_page');
//            $json->has('car_register_times.last_page_url');
//            $json->has('car_register_times.links');
//            $json->has('car_register_times.next_page_url');
//            $json->has('car_register_times.path');
//            $json->has('car_register_times.per_page');
//            $json->has('car_register_times.prev_page_url');
//            $json->has('car_register_times.to');
//            $json->has('car_register_times.total');
//        });
//    }

    /**
     * Test store a company car register time
     *
     * @return void
     * @todo Hidden feature for next release
     * TODO: Hidden feature for next release
     */
//    public function test_store_company_car_register_time()
//    {
//        $this->actingAs(
//            $user = User::factory()
//                ->owner()
//                ->create()
//        );
//        $car = Car::factory()->create();
//        $response = $this->postJson(
//            '/api/dashboard/companies/cars/register_times/register',
//            [
//                'car_id' => $car->id,
//                'should_out_at' => '2021-10-09 08:00:00',
//                'should_return_at' => '2021-10-09 16:00:00',
//            ]
//        );
//        $response->assertStatus(201);
//        $response->assertJson(function (AssertableJson $json) {
//            $json->has('car_register_time');
//            $json->has('car_register_time.id');
//
//            // status meta
//            $json->where('status', 'success');
//            $json->has('message');
//        });
//    }

    /**
     * Test store a company car worklist time
     *
     * @return void
     * @todo Hidden feature for next release
     * TODO: Hidden feature for next release
     */
//    public function test_store_company_car_worklist_time()
//    {
//        $this->actingAs(
//            $user = User::factory()
//                ->owner()
//                ->create()
//        );
//        $car = Car::factory()->create();
//        $worklist = Worklist::factory()->create();
//        $response = $this->postJson(
//            '/api/dashboard/companies/cars/register_times/register_to_worklist',
//            [
//                'car_id' => $car->id,
//                'worklist_id' => $worklist->id,
//                'should_out_at' => '2021-10-09 08:00:00',
//                'should_return_at' => '2021-10-09 16:00:00',
//            ]
//        );
//        $response->assertStatus(201);
//        $response->assertJson(function (AssertableJson $json) {
//            $json->has('car_register_time');
//            $json->has('car_register_time.id');
//
//            // status meta
//            $json->where('status', 'success');
//            $json->has('message');
//        });
//    }

    /**
     * Test update a company car register time as out
     *
     * @return void
     * @todo Hidden feature for next release
     * TODO: Hidden feature for next release
     */
//    public function test_update_company_car_register_time_as_out()
//    {
//        $this->actingAs(
//            $user = User::factory()
//                ->owner()
//                ->create()
//        );
//        $carRegisterTime = CarRegisterTime::factory()->create();
//        $response = $this->putJson(
//            '/api/dashboard/companies/cars/register_times/mark_out',
//            [
//                'car_register_time_id' => $carRegisterTime->id,
//            ]
//        );
//        $response->assertStatus(200);
//        $response->assertJson(function (AssertableJson $json) {
//            $json->has('car_register_time');
//            $json->has('car_register_time.id');
//
//            // status meta
//            $json->where('status', 'success');
//            $json->has('message');
//        });
//    }

    /**
     * Test update a company car register time as returned
     *
     * @return void
     * @todo Hidden feature for next release
     * TODO: Hidden feature for next release
     */
//    public function test_update_company_car_register_time_as_returned()
//    {
//        $this->actingAs(
//            $user = User::factory()
//                ->owner()
//                ->create()
//        );
//        $carRegisterTime = CarRegisterTime::factory()->create();
//        $response = $this->putJson(
//            '/api/dashboard/companies/cars/register_times/mark_return',
//            [
//                'car_register_time_id' => $carRegisterTime->id,
//            ]
//        );
//        $response->assertStatus(200);
//        $response->assertJson(function (AssertableJson $json) {
//            $json->has('car_register_time');
//            $json->has('car_register_time.id');
//
//            // status meta
//            $json->where('status', 'success');
//            $json->has('message');
//        });
//    }

    /**
     * Test delete a company car register time
     *
     * @return void
     * @todo Hidden feature for next release
     * TODO: Hidden feature for next release
     */
//    public function test_delete_company_car_register_time()
//    {
//        $this->actingAs(
//            $user = User::factory()
//                ->owner()
//                ->create()
//        );
//        $carRegisterTime = CarRegisterTime::factory()->create();
//        $response = $this->deleteJson(
//            '/api/dashboard/companies/cars/register_times/unregister',
//            [
//                'car_register_time_id' => $carRegisterTime->id,
//            ]
//        );
//        $response->assertStatus(200);
//        $response->assertJson(function (AssertableJson $json) {
//            // status meta
//            $json->where('status', 'success');
//            $json->has('message');
//        });
//    }

    /**
     * Test delete a company car register time permanently
     *
     * @return void
     * @todo Hidden feature for next release
     * TODO: Hidden feature for next release
     */
//    public function test_delete_company_car_register_time_permanently()
//    {
//        $this->actingAs(
//            $user = User::factory()
//                ->owner()
//                ->create()
//        );
//        $carRegisterTime = CarRegisterTime::factory()->create();
//        $response = $this->deleteJson(
//            '/api/dashboard/companies/cars/register_times/unregister',
//            [
//                'car_register_time_id' => $carRegisterTime->id,
//                'force' => true,
//            ]
//        );
//        $response->assertStatus(200);
//        $response->assertJson(function (AssertableJson $json) {
//            // status meta
//            $json->where('status', 'success');
//            $json->has('message');
//        });
//    }
}
