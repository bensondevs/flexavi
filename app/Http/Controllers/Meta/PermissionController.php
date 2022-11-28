<?php

namespace App\Http\Controllers\Meta;

use App\Http\Controllers\Controller;
use App\Http\Resources\Permission\ModuleResource;
use App\Models\Permission\Module;
use App\Models\Permission\Role;
use Illuminate\Http\JsonResponse;

/**
 * @see \Tests\Feature\Meta\PermissionTest
 *      To the controller unit tester class.
 */
class PermissionController extends Controller
{
    /**
     * Populate the owner module permissions.
     *
     * @return JsonResponse
     */
    public function owner(): JsonResponse
    {
        $role = Role::whereName(\App\Enums\Role::Owner)->first();
        $modules = Module::with('modulePermissions')
            ->whereRoleId($role->id)
            ->get();
        $modules = ModuleResource::collection($modules);

        return response()->json(['modules' => $modules]);
    }

    /**
     * Populate the employee module permissions.
     *
     * @return JsonResponse
     */
    public function employee(): JsonResponse
    {
        $role = Role::whereName(\App\Enums\Role::Employee)->first();
        $modules = Module::with('modulePermissions')
            ->whereRoleId($role->id)
            ->get();
        $modules = ModuleResource::collection($modules);

        return response()->json(['modules' => $modules]);
    }
}
