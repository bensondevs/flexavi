<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

use App\Models\User;
use App\Models\Workday;
use App\Models\Worklist;

class WorklistPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $user->hasPermissionTo('view any worklists');
    }

    /**
     * Determine whether the user can view any models under workdays.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function viewAnyWorkday(User $user, Workday $workday)
    {
        return $user->hasCompanyPermission($workday->company_id, 'view any worklists');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Worklist  $worklist
     * @return mixed
     */
    public function view(User $user, Worklist $worklist)
    {
        return $user->hasCompanyPermission($worklist->company_id, 'view worklists');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user, Workday $workday)
    {
        return $user->hasCompanyPermission($workday->company_id, 'create worklists');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Worklist  $worklist
     * @return mixed
     */
    public function update(User $user, Worklist $worklist)
    {
        return $user->hasCompanyPermission($worklist->company_id, 'edit worklists');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Worklist  $worklist
     * @return mixed
     */
    public function delete(User $user, Worklist $worklist)
    {
        return $user->hasCompanyPermission($worklist->company_id, 'delete worklists');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Worklist  $worklist
     * @return mixed
     */
    public function restore(User $user, Worklist $worklist)
    {
        return $user->hasCompanyPermission($worklist->company_id, 'restore worklists');
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Worklist  $worklist
     * @return mixed
     */
    public function forceDelete(User $user, Worklist $worklist)
    {
        return $user->hasCompanyPermission($worklist->company_id, 'force delete worklists');
    }
}
