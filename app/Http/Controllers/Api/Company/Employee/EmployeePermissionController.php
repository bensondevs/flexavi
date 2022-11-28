<?php

namespace App\Http\Controllers\Api\Company\Employee;

use App\Http\Controllers\Controller;
use App\Http\Requests\Company\Employees\EmployeePermissionsRequest;
use App\Http\Requests\Company\Employees\UpdateEmployeePermissionsRequest;
use App\Models\User\User;
use App\Repositories\Permission\PermissionRepository;
use Illuminate\Http\JsonResponse;

/**
 * @see \Tests\Feature\Dashboard\Company\Employee\EmployeePermissionTest
 *      To the controller unit tester class.
 */
class EmployeePermissionController extends Controller
{
    /**
     * Permission repository instance container property.
     *
     * @var PermissionRepository
     */
    private PermissionRepository $permissionRepository;

    /**
     * Create controller instance.
     *
     * @param PermissionRepository $permissionRepository
     */
    public function __construct(PermissionRepository $permissionRepository)
    {
        $this->permissionRepository = $permissionRepository;
    }

    /**
     * Get employee permissions collection.
     *
     * @param EmployeePermissionsRequest $request
     * @return JsonResponse
     * @see \Tests\Feature\Dashboard\Company\Employee\EmployeePermissionTest::test_populate_employee_permissions()
     *      To the controller method unit tester method.
     */
    public function employeePermissions(EmployeePermissionsRequest $request): JsonResponse
    {
        $employeeUser = $request->getEmployee()->user;

        return response()->json([
            'permissions' => $this->permissionRepository
                ->userPermissions($employeeUser, true),
        ]);
    }

    /**
     * Update employee permissions.
     *
     * @param UpdateEmployeePermissionsRequest $request
     * @return JsonResponse
     * @see \Tests\Feature\Dashboard\Company\Employee\EmployeePermissionTest::test_update_employee_permissions()
     *      To the controller method unit tester method.
     */
    public function update(UpdateEmployeePermissionsRequest $request): JsonResponse
    {
        $employee = $request->getEmployee();
        $permissionNames = $request->permissions();

        $user = User::findOrFail($employee->user_id);
        $updated = $user->syncPermissions($permissionNames);

        return response()->json([
            'status' => $updated ? 'success' : 'error',
            'message' => $updated ?
                'Successfully update employee permissions' :
                'Failed to update employee permissions',
        ]);
    }
}
