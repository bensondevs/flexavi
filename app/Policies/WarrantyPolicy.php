<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

use App\Models\User;
use App\Models\Warranty;
use App\Models\Appointment;

use App\Enums\Warranty\WarrantyStatus;

class WarrantyPolicy
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
        return $user->hasPermissionTo('view any warranties');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Warranty  $warranty
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Warranty $warranty)
    {
        return $user->hasCompanyPermission($warranty->company_id, 'view warranties');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user, Appointment $appointment, Work $work)
    {
        if (! $user->hasCompanyPermission($appointment->company_id, 'create warranties')) {
            return abort(403, 'Cannot assign warranty at this appointment.');
        }

        if ($appointment->company_id !== $work->company_id) {
            return abort(403, 'Cannot create warranty from this work.');
        }

        if ($work->status !== WorkStatus::Finished) {
            return abort(403, 'Can only create warranty on finished work.');
        }

        return true;
    }

    /**
     * Determine whether the user can create multiple models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function createMultiple(User $user, Appointment $appointment, $works)
    {
        if (! $user->hasCompanyPermission($appointment->company_id)) {
            return abort(403, 'You don\'t have permission to use this appointment.');
        }

        foreach ($works as $work) {
            if ($work->company_id != $appointment->company_id) {
                return abort(403, 'Work ID: ' . $work->id . ' is not owned by the company.');
            }

            if ($work->status !== WorkStatus::Finished) {
                return abort(403, 'Work ID: ' . $work->id . ' is not finished yet.');
            }
        }

        return true;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Warranty  $warranty
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Warranty $warranty)
    {
        return $user->hasCompanyPermission($warranty->company_id, 'edit warranties');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Warranty  $warranty
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Warranty $warranty)
    {
        return $user->hasCompanyPermission($warranty->company_id, 'delete warranties');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Warranty  $warranty
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Warranty $warranty)
    {
        return $user->hasCompanyPermission($warranty->company_id, 'restore warranties');
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Warranty  $warranty
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Warranty $warranty)
    {
        return $user->hasCompanyPermission($warranty->company_id, 'force delete warranties');
    }
}
