<?php

namespace App\Policies\Company\Owner;

use App\Models\Owner\Owner;
use App\Models\User\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class OwnerPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param User $user
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        if ($owner = tryIsset(fn () => $user->owner)) {
            return $user->hasDirectPermissionTwo('view any owners') && $owner->isMainOwner();
        }

        return false ;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param User $user
     * @param Owner $owner
     * @return bool
     */
    public function view(User $user, Owner $owner): bool
    {
        if ($user->id === $owner->user_id) {
            return true;
        }

        return $user->hasCompanyDirectPermission($owner->company_id, 'view owners');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param User $user
     * @return bool
     */
    public function create(User $user): bool
    {
        if (!$owner = $user->owner) {
            return abort(403, 'You are not owner, cannot execute this action.');
        }

        if (!$owner->is_prime_owner) {
            return abort(403, 'You are not prime owner, please ask your prime owner to do this action');
        }

        return $user->hasDirectPermissionTwo('create owners');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param User $user
     * @param Owner $updatedOwner
     * @return bool
     */
    public function update(User $user, Owner $updatedOwner): bool
    {
        if (!$owner = $user->owner) {
            return abort(403, 'You are not owner, cannot execute this action.');
        }

        if ($user->id == $updatedOwner->user_id) {
            return true;
        }

        return $user->hasCompanyDirectPermission($updatedOwner->company_id, 'edit owners');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param User $user
     * @param Owner $owner
     * @return bool
     */
    public function restore(User $user, Owner $owner): bool
    {
        return $user->hasCompanyDirectPermission($owner->company_id, 'restore owners');
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param User $user
     * @param Owner $owner
     * @return bool
     */
    public function forceDelete(User $user, Owner $owner): bool
    {
        if (!$deletePolicy = $this->delete($user, $owner)) {
            return false;
        }

        return $user->hasCompanyDirectPermission($owner->company_id, 'force delete owners');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param User $user
     * @param Owner $deletedOwner
     * @return bool
     */
    public function delete(User $user, Owner $deletedOwner): bool
    {
        if ($deletedOwner->is_prime_owner) {
            return abort(403, 'Cannot delete prime owner.');
        }

        if (!$owner = $user->owner) {
            return abort(403, 'You are not owner, cannot execute this action.');
        }

        return $user->hasCompanyDirectPermission($deletedOwner->company_id, 'delete owners');
    }
}
