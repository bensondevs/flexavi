<?php

namespace App\Http\Controllers\Api\Company\Owner;

use App\Http\Controllers\Controller;
use App\Http\Requests\Company\Owners\OwnerPermissionsRequest;
use App\Http\Requests\Company\Owners\UpdateOwnerPermissionsRequest;
use App\Models\User\User;
use App\Repositories\Permission\PermissionRepository;
use Illuminate\Http\JsonResponse;

/**
 * @see \Tests\Feature\Dashboard\Company\Owner\OwnerPermissionTest
 *      To the controller unit tester class.
 */
class OwnerPermissionController extends Controller
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
     * Load list of owner permissions.
     *
     * @param OwnerPermissionsRequest $request
     * @return JsonResponse
     */
    public function ownerPermissions(OwnerPermissionsRequest $request): JsonResponse
    {
        $ownerUser = $request->getOwner()->user;

        return response()->json([
            'permissions' => $this->permissionRepository
                ->userPermissions($ownerUser, true),
        ]);
    }

    /**
     * Update owner permissions.
     *
     * @param UpdateOwnerPermissionsRequest $request
     * @return JsonResponse
     */
    public function update(UpdateOwnerPermissionsRequest $request): JsonResponse
    {
        $owner = $request->getOwner();
        $permissionNames = $request->permissions();

        $user = User::findOrFail($owner->user_id);
        $updated = $user->syncPermissions($permissionNames);

        return response()->json([
            'status' => $updated ? 'success' : 'error',
            'message' => $updated ?
                'Successfully update owner permissions' :
                'Failed to update owner permissions',
        ]);
    }
}
