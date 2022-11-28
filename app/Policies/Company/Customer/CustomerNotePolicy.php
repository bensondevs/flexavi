<?php

namespace App\Policies\Company\Customer;

use App\Models\User\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CustomerNotePolicy
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
        return $user->hasPermissionTo('view any customer notes');
    }

    /**
     * Determine whether the user can view model.
     *
     * @param User $user
     * @return bool
     */
    public function view(User $user): bool
    {
        return $user->hasPermissionTo('view customer notes');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param User $user
     * @return bool
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create customer notes');
    }

    /**
     * Determine whether the user can edit models.
     *
     * @param User $user
     * @return bool
     */
    public function edit(User $user): bool
    {
        return $user->hasPermissionTo('edit customer notes');
    }

    /**
     * Determine whether the user can restore models.
     *
     * @param User $user
     * @return bool
     */
    public function restore(User $user): bool
    {
        return $user->hasPermissionTo('restore customer notes');
    }

    /**
     * Determine whether the user can force delete models.
     *
     * @param User $user
     * @return bool
     */
    public function forceDelete(User $user): bool
    {
        return $user->hasPermissionTo('force delete customer notes');
    }

    /**
     * Determine whether the user can delete models.
     *
     * @param User $user
     * @return bool
     */
    public function delete(User $user): bool
    {
        return $user->hasPermissionTo('delete customer notes');
    }
}
