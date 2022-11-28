<?php

namespace Tests\Feature\Dashboard\Company\Fleet;

use Tests\TestCase;

class CarRegisterTimeEmployeeTest extends TestCase
{
    /**
     * Test populate company car register time employees
     *
     * @return void
     * @todo Hidden feature for next release
     * TODO: Hidden feature for next release
     */
//    public function test_populate_company_car_register_time_employees()
//    {
//        $this->actingAs(
//            $user = User::factory()
//                ->owner()
//                ->create()
//        );
//        $carRegisterTime = CarRegisterTime::factory()->create();
//        $response = $this->getJson(
//            '/api/dashboard/companies/cars/register_times/assigned_employees?car_register_time_id=' .
//            $carRegisterTime->id
//        );
//        $response->assertStatus(200);
//        $response->assertJson(function (AssertableJson $json) {
//            $json->has('assigned_employees');
//            $json->whereType('assigned_employees.data', 'array');
//
//            // pagination meta
//            $json->has('assigned_employees.current_page');
//            $json->has('assigned_employees.first_page_url');
//            $json->has('assigned_employees.from');
//            $json->has('assigned_employees.last_page');
//            $json->has('assigned_employees.last_page_url');
//            $json->has('assigned_employees.links');
//            $json->has('assigned_employees.next_page_url');
//            $json->has('assigned_employees.path');
//            $json->has('assigned_employees.per_page');
//            $json->has('assigned_employees.prev_page_url');
//            $json->has('assigned_employees.to');
//            $json->has('assigned_employees.total');
//        });
//    }

    /**
     * Test get a company car register time employee
     *
     * @return void
     * @todo Hidden feature for next release
     * TODO: Hidden feature for next release
     */
//    public function test_get_company_car_register_time_employee()
//    {
//        $this->actingAs(
//            $user = User::factory()
//                ->owner()
//                ->create()
//        );
//        $carRegisterTimeEmployee = CarRegisterTimeEmployee::factory()->create();
//        $response = $this->getJson(
//            '/api/dashboard/companies/cars/register_times/assigned_employees/show?car_register_time_employee_id=' .
//            $carRegisterTimeEmployee->id
//        );
//        $response->assertStatus(200);
//        $response->assertJson(function (AssertableJson $json) {
//            $json->has('assigned_employee');
//            $json->has('assigned_employee.id');
//        });
//    }

    /**
     * Test assign an employee to company car register time
     *
     * @return void
     * @todo Hidden feature for next release
     * TODO: Hidden feature for next release
     */
//    public function test_assign_employee_to_company_car_register_time()
//    {
//        $this->actingAs(
//            $user = User::factory()
//                ->owner()
//                ->create()
//        );
//        $carRegisterTime = CarRegisterTime::factory()->create();
//        $employee = Employee::factory()->create();
//        $response = $this->postJson(
//            '/api/dashboard/companies/cars/register_times/assigned_employees/assign',
//            [
//                'car_register_time_id' => $carRegisterTime->id,
//                'employee_id' => $employee->id,
//                'passanger_type' => 2,
//            ]
//        );
//        $response->assertStatus(201);
//        $response->assertJson(function (AssertableJson $json) {
//            $json->has('assigned_employee');
//            $json->has('assigned_employee.id');
//
//            // status meta
//            $json->where('status', 'success');
//            $json->has('message');
//        });
//    }

    /**
     * Test update a company car register time employee as driver
     *
     * @return void
     * @todo Hidden feature for next release
     * TODO: Hidden feature for next release
     */
//    public function test_update_company_car_register_time_employee_as_driver()
//    {
//        $this->actingAs(
//            $user = User::factory()
//                ->owner()
//                ->create()
//        );
//        $carRegisterTimeEmployee = CarRegisterTimeEmployee::factory()->create();
//        $response = $this->putJson(
//            '/api/dashboard/companies/cars/register_times/assigned_employees/set_as_driver',
//            [
//                'car_register_time_employee_id' => $carRegisterTimeEmployee->id,
//            ]
//        );
//        $response->assertStatus(200);
//        $response->assertJson(function (AssertableJson $json) {
//            $json->has('assigned_employee');
//            $json->has('assigned_employee.id');
//
//            // status meta
//            $json->where('status', 'success');
//            $json->has('message');
//        });
//    }

    /**
     * Test update a company car register time employee as out
     *
     * @return void
     * @todo Hidden feature for next release
     * TODO: Hidden feature for next release
     */
//    public function test_update_company_car_register_time_employee_as_out()
//    {
//        $this->actingAs(
//            $user = User::factory()
//                ->owner()
//                ->create()
//        );
//        $carRegisterTimeEmployee = CarRegisterTimeEmployee::factory()->create();
//        $response = $this->putJson(
//            '/api/dashboard/companies/cars/register_times/assigned_employees/set_out',
//            [
//                'car_register_time_employee_id' => $carRegisterTimeEmployee->id,
//            ]
//        );
//        $response->assertStatus(200);
//        $response->assertJson(function (AssertableJson $json) {
//            $json->has('assigned_employee');
//            $json->has('assigned_employee.id');
//
//            // status meta
//            $json->where('status', 'success');
//            $json->has('message');
//        });
//    }

    /**
     * Test unassign an employee from company car register time
     *
     * @return void
     * @todo Hidden feature for next release
     * TODO: Hidden feature for next release
     */
//    public function test_unassign_employee_from_company_car_register_time()
//    {
//        $this->actingAs(
//            $user = User::factory()
//                ->owner()
//                ->create()
//        );
//        $carRegisterTimeEmployee = CarRegisterTimeEmployee::factory()->create();
//        $response = $this->deleteJson(
//            '/api/dashboard/companies/cars/register_times/assigned_employees/unassign',
//            [
//                'car_register_time_employee_id' => $carRegisterTimeEmployee->id,
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
