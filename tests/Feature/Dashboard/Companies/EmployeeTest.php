<?php

namespace Tests\Feature\Dashboard\Companies;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Models\Owner;
use App\Models\Company;
use App\Models\Employee;

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
        $owner = Owner::whereHas('user')->first();
        $user = $owner->user;
        $token = $user->generateToken();

        $headers = [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ];
        $url = '/api/dashboard/companies/employees';
        $response = $this->withHeaders($headers)->get($url);

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
        $owner = Owner::whereHas('user')->first();
        $user = $owner->user;
        $token = $user->generateToken();

        $headers = [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ];
        $url = '/api/dashboard/companies/employees/inviteables';
        $response = $this->withHeaders($headers)->get($url);

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
        $owner = Owner::whereHas('user')->first();
        $user = $owner->user;
        $token = $user->generateToken();

        $headers = [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ];
        $url = '/api/dashboard/companies/employees/trasheds';
        $response = $this->withHeaders($headers)->get($url);

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
        $owner = Owner::whereHas('user')->first();
        $user = $owner->user;
        $token = $user->generateToken();

        $headers = [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ];
        $employeeData = [
            'title' => 'The example title',
            'employee_type' => 1,
        ];
        $url = '/api/dashboard/companies/employees/store';
        $response = $this->withHeaders($headers)->post($url, $employeeData);

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
        $owner = Owner::whereHas('user')->first();
        $user = $owner->user;
        $token = $user->generateToken();

        $employee = Employee::where('company_id', $owner->company_id)->first();

        $headers = [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ];
        $employeeData = [
            'id' => $employee->id,
            'title' => 'The new employee',
            'employee_type' => 2,
        ];
        $url = '/api/dashboard/companies/employees/update';
        $response = $this->withHeaders($headers)->patch($url, $employeeData);

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
        $owner = Owner::whereHas('user')->first();
        $user = $owner->user;
        $token = $user->generateToken();

        $employee = Employee::where('company_id', $owner->company_id)->first();

        $headers = [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ];
        $url = '/api/dashboard/companies/employees/view?id=' . $employee->id;
        $response = $this->withHeaders($headers)->get($url);

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
        $company = Company::whereHas('employees')->first();
        $owner = $company->owners()->whereHas('user')->first();
        $user = $owner->user;
        $token = $user->generateToken();

        $employee = Employee::where('company_id', $owner->company_id)->first();

        $headers = [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ];
        $employeeData = ['id' => $employee->id];
        $url = '/api/dashboard/companies/employees/delete';
        $response = $this->withHeaders($headers)->delete($url, $employeeData);

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
        $owner = Owner::whereHas('user')->first();
        $user = $owner->user;
        $token = $user->generateToken();

        $employee = Employee::where('company_id', $owner->company_id)->first();
        $employeeId = $employee->id;
        $employee->delete();

        $headers = [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ];
        $employeeData = ['id' => $employeeId];
        $url = '/api/dashboard/companies/employees/restore';
        $response = $this->withHeaders($headers)->patch($url, $employeeData);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('status');
            $json->has('message');
            $json->has('employee');

            $json->where('status', 'success');
        });
    }
}
