<?php

namespace App\Policies\Company\Permission;

use App\Models\User\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PermissionPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $user->hasDirectPermissionTwo('view any permissions');
    }
}
