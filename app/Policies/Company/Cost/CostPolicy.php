<?php

namespace App\Policies\Company\Cost;

use App\Models\{Cost\Cost, Cost\Costable, User\User};
use Illuminate\Auth\Access\HandlesAuthorization;

class CostPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        return $user->hasDirectPermissionTwo('view any costs');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User\User  $user
     * @param  \App\Models\Cost\Cost  $cost
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Cost $cost)
    {
        return $user->hasCompanyDirectPermission($cost->company_id, 'view costs');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $user->hasDirectPermissionTwo('create costs');
    }

    /**
     * Determine whether the user can record models.
     *
     * @param  \App\Models\User\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function record(User $user, Cost $cost, $costable)
    {
        if ($cost->company_id != $costable->company_id) {
            return abort(403, 'Cannot assign data from other company.');
        }

        if (Costable::isAlreadyAttached($cost, $costable)) {
            return abort(422, 'This cost has been attached');
        }

        return $user->hasCompanyDirectPermission($cost->company_id, 'record costs');
    }

    /**
     * Determine whether the user can record many models.
     *
     * @param  \App\Models\User\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function recordMany(User $user, $costable)
    {
        return $user->hasCompanyDirectPermission($costable->company_id, 'record costs');
    }

    /**
     * Determine whether the user can unrecord models.
     *
     * @param  \App\Models\User\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function unrecord(User $user, Cost $cost, $costable)
    {
        if ($cost->company_id != $costable->company_id) {
            return abort(403, 'Cannot assign data from other company.');
        }

        return $user->hasCompanyDirectPermission($cost->company_id, 'unrecord costs');
    }

    /**
     * Determine whether the user can unrecord many models.
     *
     * @param  \App\Models\User\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function unrecordMany(User $user, $costable)
    {
        return $user->hasCompanyDirectPermission($costable->company_id, 'unrecord costs');
    }

    /**
     * Determine whether the user can truncate models.
     *
     * @param  \App\Models\User\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function truncate(User $user, $costable)
    {
        return $user->hasCompanyDirectPermission($costable->company_id, 'truncate costs');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User\User  $user
     * @param  \App\Models\Cost\Cost  $cost
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Cost $cost)
    {
        return $user->hasCompanyDirectPermission($cost->company_id, 'edit costs');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User\User  $user
     * @param  \App\Models\Cost\Cost  $cost
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Cost $cost)
    {
        return $user->hasCompanyDirectPermission($cost->company_id, 'delete costs');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User\User  $user
     * @param  \App\Models\Cost\Cost  $cost
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Cost $cost)
    {
        return $user->hasCompanyDirectPermission($cost->company_id, 'restore costs');
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User\User  $user
     * @param  \App\Models\Cost\Cost  $cost
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Cost $cost)
    {
        return $user->hasCompanyDirectPermission($cost->company_id, 'force delete costs');
    }
}
