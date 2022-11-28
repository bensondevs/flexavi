<?php

namespace App\Policies\Company\Log;

use App\Models\Log\Log;
use App\Models\User\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Collection;

class LogPolicy
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
        return $user->hasDirectPermissionTwo('view any logs');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User\User  $user
     * @param  \App\Models\Log\Log  $log
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Log $log)
    {
        return $user->hasCompanyDirectPermission($log->company_id, 'restore logs');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User\User  $user
     * @param  Collection|array $log
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restoreMany(User $user, $logs)
    {
        return $user->hasDirectPermissionTwo('restore logs') &&
            in_array(
                $user->company->id,
                $logs instanceof Collection ? $logs->pluck("company_id")->toArray() : \Arr::pluck($logs, "company_id")
            );
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User\User  $user
     * @param  \App\Models\Log\Log  $log
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Log $log)
    {
        return $user->hasCompanyDirectPermission($log->company_id, 'delete logs');
    }

    /**
     * Determine whether the user can delete many the model.
     *
     * @param  \App\Models\User\User  $user
     * @param  Collection|array $log
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function deleteMany(User $user, $logs)
    {
        return $user->hasDirectPermissionTwo('delete logs') &&
            in_array(
                $user->company->id,
                $logs instanceof Collection ? $logs->pluck("company_id")->toArray() : \Arr::pluck($logs, "company_id")
            );
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User\User  $user
     * @param  \App\Models\Log\Log  $log
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Log $log)
    {
        return $user->hasCompanyDirectPermission($log->company_id, 'force delete logs');
    }

    /**
     * Determine whether the user can delete many the model.
     *
     * @param  \App\Models\User\User  $user
     * @param  Collection|array $log
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDeleteMany(User $user, $logs)
    {
        return $user->hasDirectPermissionTwo('force delete logs') &&
            in_array(
                $user->company->id,
                $logs instanceof Collection ? $logs->pluck("company_id")->toArray() : \Arr::pluck($logs, "company_id")
            );
    }
}
