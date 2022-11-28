<?php

namespace App\Http\Controllers\Api\Company\Permission;

use App\Http\Controllers\Api\Company\Illuminate;
use App\Http\Controllers\Controller;
use App\Http\Requests\Company\Permissions\PopulatePermissionsRequest;
use App\Repositories\Permission\PermissionRepository;

class PermissionController extends Controller
{
    /**
     * permission Repository Class Container
     *
     * @var PermissionRepository
     */
    private $permission;

    /**
     * Controller constructor method
     *
     * @param PermissionRepository $permission
     * @return void
     */
    public function __construct(
        PermissionRepository $permission
    )
    {
        $this->permission = $permission;
    }


    /**
     * Get current requesting user
     *
     * @return Illuminate\Support\Facades\Response
     */
    public function userablePermissions(PopulatePermissionsRequest $request)
    {
        return response()->json([
            'permissions' => $this->permission->userPermissions($request->getCurrentUser(), true)
        ]);
    }
}
