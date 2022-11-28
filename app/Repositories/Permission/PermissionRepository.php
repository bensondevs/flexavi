<?php

namespace App\Repositories\Permission;

use Illuminate\Support\Collection;
use App\Models\{Permission\Permission, User\User};
use App\Repositories\Base\BaseRepository;
use Illuminate\Database\QueryException;

class PermissionRepository extends BaseRepository
{
    /**
     * Repository constructor method
     *
     * @return void
     */
    public function __construct()
    {
        $this->setInitModel(new Permission());
    }

    /**
     * Update user permission
     *
     * @param User $user
     * @param array $permissions
     * @return bool
     */
    public function syncPermissions(User $user, array $permissions): bool
    {
        try {
            $user->permissions()->detach();
            $permissions = array_filter(
                array_map(function ($permission) {
                    if ($permission instanceof Permission) {
                        return $permission;
                    }
                    if (is_uuid($permission)) {
                        return Permission::findById($permission);
                    }
                    if ($permission === Permission::findByName($permission)) {
                        return $permission;
                    }
                }, $permissions)
            );
            $user->permissions()->attach($permissions);
            $this->setSuccess('Successfully updated the user permissions');
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setError('Failed to update user permission.', $error);
        }

        return $this->returnResponse();
    }

    /**
     * Populate permissions names.
     *
     * @return array
     */
    public function permissionNames(): array
    {
        return Permission::all('name')
            ->pluck('name')
            ->toArray();
    }

    /**
     * Populate user permission
     *
     * @param User $user
     * @param bool $includeInactive
     * @return Collection
     */
    public function userPermissions(
        User $user,
        bool $includeInactive = false
    ): Collection {
        $userPermissions = $user->getPermissionNames();

        if (!$includeInactive) {
            return collect($userPermissions)->map(fn ($permissionName) => [
                'name' => $permissionName,
                'active' => true,
            ]);
        }

        $permissionNames = $this->permissionNames();

        return collect($permissionNames)->map(function ($permissionName) use ($userPermissions) {
            $permissionFound = collect($userPermissions)
                ->filter(fn ($userPermission) => $userPermission === $permissionName)
                ->count() > 0;

            return [
                'name' => $permissionName,
                'active' => $permissionFound,
            ];
        });
    }
}
