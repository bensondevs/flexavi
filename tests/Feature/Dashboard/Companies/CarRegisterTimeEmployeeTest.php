<?php

namespace Tests\Feature\Dashboard\Companies;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;

use App\Models\{ 
    Company, 
    Owner, 
    Employee, 
    CarRegisterTime, 
    CarRegisterTimeEmployee as AssignedEmployee 
};

class CarRegisterTimeEmployeeTest extends TestCase
{
    private $baseUrl = '/api/dashboard/companies/cars/register_times/assigned_employees';

    /**
     * A populate car register time employees test.
     *
     * @return void
     */
    public function test_view_all_assigned_employees()
    {
        $company = Company::inRandomOrder()->first();
        $owner = Owner::factory()->for($company)->create();
        $user = $owner->user;
        Sanctum::actingAs($user, ['*']);

        $time = CarRegisterTime::factory()->for($company)->create();
        $url = $this->baseUrl . '?car_register_time_id=' . $time->id;
        $response = $this->json('GET', $url);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('assigned_employees');
        });
    }

    /**
     * Assign employee to car register time test.
     *
     * @return void
     */
    public function test_assign_employee_to_car_register_time()
    {
        $company = Company::inRandomOrder()->first();
        $owner = Owner::factory()->for($company)->create();
        $user = $owner->user;
        Sanctum::actingAs($user, ['*']);

        $time = CarRegisterTime::factory()->for($company)->create();
        $employee = Employee::factory()->for($company)->create();
        $url = $this->baseUrl . '/assign';
        $response = $this->json('POST', $url, [
            'car_register_time_id' => $time->id,
            'employee_id' => $employee->id,
            'passanger_type' => 1,
        ]);

        $response->assertStatus(201);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('message');
            $json->where('status', 'success');
        });
    }

    /**
     * Set assigned employee in car register time as driver test.
     *
     * @return void
     */
    public function test_set_assigned_employee_as_driver()
    {
        $company = Company::inRandomOrder()->first();
        $owner = Owner::factory()->for($company)->create();
        $user = $owner->user;
        Sanctum::actingAs($user, ['*']);

        $assignedEmployee = AssignedEmployee::factory()
            ->for($company)
            ->create();
        $url = $this->baseUrl . '/set_as_driver';
        $response = $this->json('POST', $url, [
            'car_register_time_employee_id' => $assignedEmployee->id,
        ]);

        $response->assertStatus(201);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('message');
            $json->where('status', 'success');
        });
    }

    /**
     * Set assigned employee out from car test.
     *
     * @return void
     */
    public function test_set_assigned_employee_out()
    {
        $company = Company::inRandomOrder()->first();
        $owner = Owner::factory()->for($company)->create();
        $user = $owner->user;
        Sanctum::actingAs($user, ['*']);

        $assignedEmployee = AssignedEmployee::factory()
            ->for($company)
            ->create();
        $url = $this->baseUrl . '/set_out';
        $response = $this->json('POST', $url, [
            'car_register_time_employee_id' => $assignedEmployee->id,
        ]);

        $response->assertStatus(201);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('message');
            $json->where('status', 'success');
        });
    }

    /**
     * Unassign employee from car register time test.
     *
     * @return void
     */
    public function test_unassign_employee_from_car_register_time()
    {
        $company = Company::inRandomOrder()->first();
        $owner = Owner::factory()->for($company)->create();
        $user = $owner->user;
        Sanctum::actingAs($user, ['*']);

        $assignedEmployee = AssignedEmployee::factory()
            ->for($company)
            ->create();
        $url = $this->baseUrl . '/unassign';
        $response = $this->json('DELETE', $url, [
            'car_register_time_employee_id' => $assignedEmployee->id
        ]);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('message');
            $json->where('status', 'success');
        });
    }
}
