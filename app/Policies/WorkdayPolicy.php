<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

use App\Models\User;
use App\Models\Workday;

use App\Enums\Workday\WorkdayStatus;

class WorkdayPolicy
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
        return $user->hasPermissionTo('view any workdays');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Workday  $workday
     * @return mixed
     */
    public function view(User $user, Workday $workday)
    {
        return $user->hasCompanyPermission($workday->company_id, 'view workdays');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return false;
    }

    /**
     * Determine whether the user can process models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function process(User $user, Workday $workday)
    {
        if ($workday->status >= WorkdayStatus::Processed) {
            return abort(422, 'Failed to process workday. Workday had been processed in the past.');
        }

        return $user->hasCompanyPermission($workday->company_id, 'process workdays');
    }

    /**
     * Determine whether the user can calculate models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function calculate(User $user, Workday $workday)
    {
        return $user->hasCompanyPermission($workday->company_id, 'calculate workdays');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Workday  $workday
     * @return mixed
     */
    public function update(User $user, Workday $workday)
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Workday  $workday
     * @return mixed
     */
    public function delete(User $user, Workday $workday)
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Workday  $workday
     * @return mixed
     */
    public function restore(User $user, Workday $workday)
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Workday  $workday
     * @return mixed
     */
    public function forceDelete(User $user, Workday $workday)
    {
        return false;
    }
}
