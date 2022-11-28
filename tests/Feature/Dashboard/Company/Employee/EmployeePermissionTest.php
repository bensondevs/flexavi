<?php

namespace Tests\Feature\Dashboard\Company\Employee;

use App\Http\Controllers\Api\Company\Employee\EmployeePermissionController;
use App\Models\Employee\Employee;
use App\Models\User\User;
use App\Repositories\Permission\PermissionRepository;
use App\Traits\FeatureTestUsables;
use Tests\TestCase;

/**
 * @see EmployeePermissionController
 *      To the tested controller class.
 */
class EmployeePermissionTest extends TestCase
{
    use FeatureTestUsables;

    /**
     * Test populate employee permissions works.
     *
     * @test
     * @return void
     * @see \App\Http\Controllers\Api\Company\Employee\EmployeePermissionController::employeePermissions()
     *      To the tested controller method.
     */
    public function test_populate_employee_permissions(): void
    {
        $user = $this->authenticateAsOwner();
        $company = $user->owner->company;

        // Prepare employee as the subject of test
        $employee = Employee::factory()->create([
            'company_id' => $company->id,
        ])->fresh();
        $employee->user->syncPermissions(
            $assignedPermissions = collect(app(PermissionRepository::class)
                ->permissionNames())->take(rand(3, 5))
        );

        // Make request to the controller method endpoint URL
        $url = '/api/dashboard/companies/employees/permissions/?employee_id=' . $employee->id;
        $response = $this->getJson($url);

        // Assert the response is success as expected
        $response->assertSuccessful();
        $content = $response->getOriginalContent();
        $permissions = collect($content['permissions']);
        foreach ($assignedPermissions as $assignedPermission) {
            $found = $permissions->where('name', $assignedPermission)->first();
            $found ?
                $this->assertTrue($found['active']) :
                $this->assertFalse($found['active']);
        }
    }

    /**
     * Test update employee permissions.
     *
     * @test
     * @return void
     * @see \App\Http\Controllers\Api\Company\Employee\EmployeePermissionController::update()
     *      To the tested controller method.
     */
    public function test_update_employee_permissions(): void
    {
        $user = $this->authenticateAsOwner();
        $company = $user->owner->company;

        // Prepare employee as the subject of test
        $employee = Employee::factory()->create([
            'company_id' => $company->id,
        ])->fresh();

        // Prepare new permissions to be attached to employee
        $newPermissions = collect(
            app(PermissionRepository::class)->permissionNames()
        )->take(rand(3, 5))->toArray();

        // Prepare the endpoint URL
        $url = '/api/dashboard/companies/employees/permissions/update/';

        /**
         * Test using array input
         */
        // Make request to the controller method endpoint URL
        $response = $this->postJson($url, [
            'employee_id' => $employee->id,
            'permission_names' => $newPermissions,
        ]);

        // Assert the response is success as expected
        $response->assertSuccessful();

        // Assert the employee does have the permission
        $employeeUser = $employee->user;
        foreach ($newPermissions as $newPermission) {
            $employeeUser->hasPermissionTo($newPermission);
        }

        /**
         * Test using JSON array input
         */
        // Make request to the controller method endpoint URL
        $response = $this->postJson($url, [
            'employee_id' => $employee->id,
            'permission_names' => json_encode($newPermissions),
        ]);

        // Assert the response is success as expected
        $response->assertSuccessful();

        // Assert the employee does have the permission
        foreach ($newPermissions as $newPermission) {
            $employeeUser->hasPermissionTo($newPermission);
        }

        /**
         * Test using JSON string input
         */
        // Make request to the controller method endpoint URL
        $response = $this->postJson($url, [
            'employee_id' => $employee->id,
            'permission_names' => $newPermissions[0],
        ]);

        // Assert the response is success as expected
        $response->assertSuccessful();

        // Assert the employee does have the permission
        foreach ($newPermissions as $newPermission) {
            $employeeUser->hasPermissionTo($newPermission);
        }
    }
}
