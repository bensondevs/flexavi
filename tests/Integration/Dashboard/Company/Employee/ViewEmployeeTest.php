<?php

namespace Tests\Integration\Dashboard\Company\Employee;

use App\Models\User\User;
use Database\Factories\AddressFactory;
use Database\Factories\EmployeeFactory;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

/**
 *  @see App\Http\Controllers\Api\Company\Employee\EmployeeController::view()
 *      to the tested controller method
 */
class ViewEmployeeTest extends TestCase
{
    use WithFaker;

    /**
    * Test view employee with employee's relation
    *
    * @return void
    */
    public function test_view_employee_with_relation(): void
    {
        $this->actingAs(
            $user = User::factory()
                ->owner()
                ->create()
        );
        $company = $user->owner->company;

        $employee = EmployeeFactory::new()->for($company)->create();
        $address = AddressFactory::new()->employee($employee)->create(); // create address for the employee

        $response = $this->getJson(
            "/api/dashboard/companies/employees/view?id=$employee->id" .
            "&with_company=true" . "&with_user=true" . "&with_address=true"
        );

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) use ($company, $employee, $address) {
            $employee = $employee->fresh();

            $json->has('employee');
            $json->where('employee.id', $employee->id);

            // model's relationship assertions
            $json->where('employee.company.id', $employee->company->id);
            $json->where('employee.user.id', $employee->user->id);
            $json->where('employee.address.id', $address->id);
        });
    }

    /**
    * Test view employee without employee's relation
    *
    * @return void
    */
    public function test_view_employee_without_relation(): void
    {
        $this->actingAs(
            $user = User::factory()
                ->owner()
                ->create()
        );
        $company = $user->owner->company;

        $employee = EmployeeFactory::new()->for($company)->create();
        $address = AddressFactory::new()->employee($employee)->create(); // create address for the employee

        $response = $this->getJson(
            "/api/dashboard/companies/employees/view?id=$employee->id" .
            "&with_company=false" . "&with_user=false" . "&with_address=false"
        )->assertStatus(200);

        $content = json_decode($response->getContent());

        $this->assertEquals($employee->id, $content->employee->id);

        $employee = (array) $content->employee;
        $this->assertArrayNotHasKey('company', $employee);
        $this->assertArrayNotHasKey('user', $employee);
        $this->assertArrayNotHasKey('address', $employee);
    }
}
