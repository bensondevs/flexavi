<?php

namespace App\Repositories;

use \Illuminate\Support\Facades\DB;
use \Illuminate\Database\QueryException;

use App\Models\User;
use App\Models\Role;
use App\Models\Permission;

use App\Repositories\Base\BaseRepository;

class PermissionRepository extends BaseRepository
{
	protected $role;
	protected $permission;

	public function __construct()
	{
		$this->role = new Role;
		$this->permission = new Permission;

		// Set default model
		$this->setInitModel(new Permission);
	}

	public function createRole($roleName)
    {
    	try {
    		$role = Role::create(['name' => $roleName]);

    		$this->role = $role;

    		$this->setSuccess('Successfully create new role.');
    	} catch (QueryException $qe) {
    		$this->setError('Failed to create new role.', $qe->getMessage());
    	}

    	return $this->role;
    }

    public function createPermission($permissionName)
    {
    	try {
    		$permission = Permission::create(['name' => $permissionName]);

    		$this->permission = $permission;

    		$this->setSuccess('Successfully create new role.');
    	} catch (QueryException $qe) {
    		$this->setError('Failed to create new role.', $qe->getMessage());
    	}

    	return ($this->status == 'success') ?
            $permission : null;
    }

	public function assignRole(User $user, $roleName)
	{
        try {
            $role = Role::findByName($roleName);
            $user->assignRole($role);

            $this->setSuccess('Successfully assign role to user.');
        } catch (QueryException $qe) {
            $this->setError('Failed to assign role to user.', $qe->getMessage());
        }

        return $user;
	}

    public function revokeRole(User $user, $roleName)
    {
        try {
            $user->revokeRole($roleName);
        } catch (QueryException $qe) {
            
        }
    }

	public function updateUserPermissions(User $user, array $updatedPermissions)
    {
        $userPermissions = $user->getPermissionNames()->toArray();

        $assignedPermissions = [];
        if ($userPermissions) {
            foreach ($updatedPermissions as $updatedPermission)
                if (! in_array($updatedPermission, $userPermissions)) 
                    array_push($assignedPermissions, $updatedPermission);
        } else {
            $assignedPermissions = $updatedPermissions;
        }


        $revokedPermissions = [];
        if ($userPermissions) {
            foreach ($userPermissions as $userPermission)
                if (! in_array($userPermission, $updatedPermissions))
                    array_push($revokedPermissions, $userPermission);
        }

        try {
            if ($assignedPermissions)
                $user->givePermissionTo($assignedPermissions);
            
            if ($revokedPermissions)
                $user->revokePermissionTo($revokedPermissions);

            $this->setSuccess('Successfully updated the user permissions');
        } catch (QueryException $qe) {
            $this->setError('Failed to update user permission.', $qe->getMessage());
        }

        return $this->returnResponse();
    }
}
