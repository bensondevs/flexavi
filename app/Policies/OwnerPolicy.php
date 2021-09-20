<?php

namespace App\Policies;

use App\Models\Owner;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class OwnerPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        return $user->hasPermissionTo('view any owners');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Owner  $owner
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Owner $owner)
    {
        if ($user->id == $owner->user_id) {
            return true;
        }

        return $user->hasCompanyPermission($owner->company_id, 'view owners');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        if (! $owner = $user->owner) {
            return abort(403, 'You are not owner, cannot execute this action.');
        }

        if (! $owner->is_prime_owner) {
            return abort(403, 'You are not prime owner, please ask your prime owner to do this action');
        }

        return $user->hasPermissionTo('create owners');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Owner  $owner
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Owner $updatedOwner)
    {
        if (! $owner = $user->owner) {
            return abort(403, 'You are not owner, cannot execute this action.');
        }

        if ($user->id == $updatedOwner->user_id) {
            return true;
        }

        return $user->hasCompanyPermission($updatedOwner->company_id, 'edit owners');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Owner  $owner
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Owner $deletedOwner)
    {
        if ($deletedOwner->is_prime_owner) {
            return abort(403, 'Cannot delete prime owner.');
        }

        if (! $owner = $user->owner) {
            return abort(403, 'You are not owner, cannot execute this action.');
        }

        return $user->hasCompanyPermission($deletedOwner->company_id, 'delete owners');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Owner  $owner
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Owner $owner)
    {
        return $user->hasCompanyPermission($owner->company_id, 'restore owners');
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Owner  $owner
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Owner $owner)
    {
        if (! $deletePolicy = $this->delete($user, $owner)) {
            return false;
        }

        return $user->hasCompanyPermission($owner->company_id, 'force delete owners');
    }
}
