<?php

namespace Tests\Feature\Dashboard\Company\Employee;

use App\Models\Employee\Employee;
use App\Models\User\User;
use App\Traits\FeatureTestUsables;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class EmployeeDataSectionTest extends TestCase
{
    use FeatureTestUsables;

    /**
     * Test populate employee quotations
     *
     * @return void
     */
    public function test_populate_employee_quotations(): void
    {
        $user = $this->authenticateAsOwner();
        $company = $user->owner->company;

        $employee = Employee::factory()->for($company)->create();

        $response = $this->getJson('/api/dashboard/companies/employees/quotations?employee_id=' . $employee->id);
        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('quotations');
            $json->whereType('quotations.data', 'array');

            // pagination meta
            $json->has('quotations.current_page');
            $json->has('quotations.first_page_url');
            $json->has('quotations.from');
            $json->has('quotations.last_page');
            $json->has('quotations.last_page_url');
            $json->has('quotations.links');
            $json->has('quotations.next_page_url');
            $json->has('quotations.path');
            $json->has('quotations.per_page');
            $json->has('quotations.prev_page_url');
            $json->has('quotations.to');
            $json->has('quotations.total');
        });
    }

    /**
     * Test populate employee warranties
     *
     * @return void
     */
    public function test_populate_employee_warranties(): void
    {
        $user = $this->authenticateAsOwner();
        $company = $user->owner->company;

        $employee = Employee::factory()->for($company)->create();

        $response = $this->getJson('/api/dashboard/companies/employees/warranties?employee_id=' . $employee->id);
        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('warranties');
            $json->whereType('warranties.data', 'array');

            // pagination meta
            $json->has('warranties.current_page');
            $json->has('warranties.first_page_url');
            $json->has('warranties.from');
            $json->has('warranties.last_page');
            $json->has('warranties.last_page_url');
            $json->has('warranties.links');
            $json->has('warranties.next_page_url');
            $json->has('warranties.path');
            $json->has('warranties.per_page');
            $json->has('warranties.prev_page_url');
            $json->has('warranties.to');
            $json->has('warranties.total');
        });
    }

    /**
     * Test populate employee inspections
     *
     * @return void
     */
    public function test_populate_employee_inspections(): void
    {
        $user = $this->authenticateAsOwner();
        $company = $user->owner->company;

        $employee = Employee::factory()->for($company)->create();

        $response = $this->getJson('/api/dashboard/companies/employees/inspections?employee_id=' . $employee->id);
        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('inspections');
            $json->whereType('inspections.data', 'array');

            // pagination meta
            $json->has('inspections.current_page');
            $json->has('inspections.first_page_url');
            $json->has('inspections.from');
            $json->has('inspections.last_page');
            $json->has('inspections.last_page_url');
            $json->has('inspections.links');
            $json->has('inspections.next_page_url');
            $json->has('inspections.path');
            $json->has('inspections.per_page');
            $json->has('inspections.prev_page_url');
            $json->has('inspections.to');
            $json->has('inspections.total');
        });
    }

    /**
     * Test populate employee worklists
     *
     * @return void
     */
    public function test_populate_employee_worklists(): void
    {
        $user = $this->authenticateAsOwner();
        $company = $user->owner->company;

        $employee = Employee::factory()->for($company)->create();

        $response = $this->getJson('/api/dashboard/companies/employees/worklists?employee_id=' . $employee->id);
        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('worklists');
            $json->whereType('worklists.data', 'array');

            // pagination meta
            $json->has('worklists.current_page');
            $json->has('worklists.first_page_url');
            $json->has('worklists.from');
            $json->has('worklists.last_page');
            $json->has('worklists.last_page_url');
            $json->has('worklists.links');
            $json->has('worklists.next_page_url');
            $json->has('worklists.path');
            $json->has('worklists.per_page');
            $json->has('worklists.prev_page_url');
            $json->has('worklists.to');
            $json->has('worklists.total');
        });
    }

    /**
     * Test populate employee document
     *
     * @return void
     */
    public function test_populate_employee_document(): void
    {
        $user = $this->authenticateAsOwner();
        $company = $user->owner->company;

        $employee = Employee::factory()->for($company)->create();

        $response = $this->getJson('/api/dashboard/companies/employees/document?employee_id=' . $employee->id);
        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('document');
        });
    }
}
