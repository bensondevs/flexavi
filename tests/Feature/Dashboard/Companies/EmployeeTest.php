<?php

namespace Tests\Feature\Dashboard\Companies;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;

use App\Models\{ Owner, Company, Employee };

class EmployeeTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * A populate employees test.
     *
     * @return void
     */
    public function test_view_all_employees()
    {
        $company = Company::inRandomOrder()->first();
        $owner = Owner::factory()->for($company)->create();
        Sanctum::actingAs(($user = $owner->user), ['*']);

        $url = '/api/dashboard/companies/employees';
        $response = $this->json('GET', $url);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('employees')
                ->has('employees.data')
                ->has('employees.current_page');
        });
    }

    /**
     * A populate inviteable employees test.
     *
     * @return void
     */
    public function test_view_inviteable_employees()
    {
        $company = Company::inRandomOrder()->first();
        $owner = Owner::factory()->for($company)->create();
        Sanctum::actingAs(($user = $owner->user), ['*']);

        $url = '/api/dashboard/companies/employees/inviteables';
        $response = $this->json('GET', $url);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('employees')
                ->has('employees.data')
                ->has('employees.current_page');
        });
    }

    /**
     * A populate trashed employees test.
     *
     * @return void
     */
    public function test_view_trashed_employees()
    {
        $company = Company::inRandomOrder()->first();
        $owner = Owner::factory()->for($company)->create();
        Sanctum::actingAs(($user = $owner->user), ['*']);

        $url = '/api/dashboard/companies/employees/trasheds';
        $response = $this->json('GET', $url);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('employees')
                ->has('employees.data')
                ->has('employees.current_page');
        });
    }

    /**
     * A store employee test.
     *
     * @return void
     */
    public function test_store_employee()
    {
        $company = Company::inRandomOrder()->first();
        $owner = Owner::factory()->for($company)->create();
        Sanctum::actingAs(($user = $owner->user), ['*']);

        $url = '/api/dashboard/companies/employees/store';
        $response = $this->json('POST', $url, [
            'title' => 'The example title',
            'employee_type' => 1,
        ]);

        $response->assertStatus(201);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('status');
            $json->has('message');

            $json->where('status', 'success');
        });
    }

    /**
     * A update employee test.
     *
     * @return void
     */
    public function test_update_employee()
    {
        $company = Company::inRandomOrder()->first();
        $owner = Owner::factory()->for($company)->create();
        Sanctum::actingAs(($user = $owner->user), ['*']);

        $employee = Employee::factory()->for($company)->create();

        $url = '/api/dashboard/companies/employees/update';
        $response = $this->json('PATCH', $url, [
            'id' => $employee->id,
            'title' => 'The new employee',
            'employee_type' => 2,
        ]);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('status');
            $json->has('message');

            $json->where('status', 'success');
        });
    }

    /**
     * A view employee test.
     *
     * @return void
     */
    public function test_view_employee()
    {
        $company = Company::inRandomOrder()->first();
        $owner = Owner::factory()->for($company)->create();
        Sanctum::actingAs(($user = $owner->user), ['*']);

        $employee = Employee::factory()->for($company)->create();

        $url = '/api/dashboard/companies/employees/view?id=' . $employee->id;
        $response = $this->json('GET', $url);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('employee');
        });
    }

    /**
     * A delete employee test.
     *
     * @return void
     */
    public function test_delete_employee()
    {
        $company = Company::inRandomOrder()->first();
        $owner = Owner::factory()->for($company)->create();
        Sanctum::actingAs(($user = $owner->user), ['*']);

        $employee = Employee::factory()->for($company)->create();
        
        $employeeData = ['id' => $employee->id];
        $url = '/api/dashboard/companies/employees/delete';
        $response = $this->json('DELETE', $url, $employeeData);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('status');
            $json->has('message');

            $json->where('status', 'success');
        });
    }

    /**
     * A restore employee test.
     *
     * @return void
     */
    public function test_restore_employee()
    {
        $company = Company::inRandomOrder()->first();
        $owner = Owner::factory()->for($company)->create();
        Sanctum::actingAs(($user = $owner->user), ['*']);

        $employee = Employee::factory()->for($company)->softDeleted()->create();

        $employeeData = ['id' => $employee->id];
        $url = '/api/dashboard/companies/employees/restore';
        $response = $this->json('PATCH', $url, $employeeData);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('status');
            $json->has('message');
            $json->has('employee');

            $json->where('status', 'success');
        });
    }
}
