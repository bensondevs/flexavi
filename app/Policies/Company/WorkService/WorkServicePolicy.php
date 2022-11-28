<?php

namespace App\Policies\Company\WorkService;

use App\Models\User\User;
use App\Models\WorkService\WorkService;
use Illuminate\Auth\Access\HandlesAuthorization;

class WorkServicePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->hasDirectPermissionTwo('view any work services');
    }

    public function view(User $user, WorkService $workService): bool
    {
        return $user->hasCompanyDirectPermission($workService->company_id, 'view work services');
    }

    public function create(User $user): bool
    {
        return $user->hasDirectPermissionTwo('create work services');
    }

    public function edit(User $user, WorkService $workService): bool
    {
        return $user->hasCompanyDirectPermission($workService->company_id, 'edit work services');
    }

    public function restore(User $user, WorkService $workService): bool
    {
        return $user->hasCompanyDirectPermission($workService->company_id, 'restore work services');
    }

    public function forceDelete(User $user, WorkService $workService): bool
    {
        if (!$workService->isDeletable()) {
            return abort(403, 'Cannot delete work service that is active and or used in other modules.');
        }

        return $user->hasCompanyDirectPermission($workService->company_id, 'force delete work services');
    }

    public function delete(User $user, WorkService $workService): bool
    {
        if (!$workService->isDeletable()) {
            return abort(403, 'Cannot delete work service that is active and or used in other modules.');
        }

        return $user->hasCompanyDirectPermission($workService->company_id, 'delete work services');
    }
}
