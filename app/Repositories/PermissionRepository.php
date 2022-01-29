<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use App\Repositories\Base\BaseRepository;

use App\Models\{ User, Role, Permission};

class PermissionRepository extends BaseRepository
{
    /**
     * Repository constructor method
     * 
     * @return void
     */
	public function __construct()
	{
		$this->setInitModel(new Permission);
	}

    /**
     * Update user permission
     * 
     * @param  \App\Models\User  $user
     * @param  array  $permissions
     * @return bool
     */
	public function syncPermissions(User $user, array $permissions)
    {
        try {
            // Remove all permissions
            $user->permissions()->detach();

            // Attach massively on user permissions
            $permissions = array_filter(array_map(function ($permission) {
                if ($permission instanceof Permission) {
                    return $permission;
                }

                if (is_uuid($permission)) {
                    return Permission::findByName($permission);
                }

                if ($permission = Permission::findByName($permission)) {
                    return $permission;
                }

                return;
            }, $permissions));
            $user->permissions()->attach($permissions);

            $this->setSuccess('Successfully updated the user permissions');
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setError('Failed to update user permission.', $error);
        }

        return $this->returnResponse();
    }
}
