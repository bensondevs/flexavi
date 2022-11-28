<?php

namespace App\Policies\Company\Revenue;

use App\Models\Revenue\Revenue;
use App\Models\User\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class RevenuePolicy
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
        return $user->hasDirectPermissionTwo('view any revenues');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User\User  $user
     * @param  \App\Models\Revenue\Revenue  $revenue
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Revenue $revenue)
    {
        return $user->hasCompanyDirectPermission($revenue->company_id, 'view revenues');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $user->hasDirectPermissionTwo('create revenues');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User\User  $user
     * @param  \App\Models\Revenue\Revenue  $revenue
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Revenue $revenue)
    {
        return $user->hasCompanyDirectPermission($revenue->company_id, 'edit revenues');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User\User  $user
     * @param  \App\Models\Revenue\Revenue  $revenue
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Revenue $revenue)
    {
        return $user->hasCompanyDirectPermission($revenue->company_id, 'delete revenues');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User\User  $user
     * @param  \App\Models\Revenue\Revenue  $revenue
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Revenue $revenue)
    {
        return $user->hasCompanyDirectPermission($revenue->company_id, 'restore revenues');
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User\User  $user
     * @param  \App\Models\Revenue\Revenue  $revenue
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Revenue $revenue)
    {
        return $user->hasCompanyDirectPermission($revenue->company_id, 'force delete revenues');
    }
}
