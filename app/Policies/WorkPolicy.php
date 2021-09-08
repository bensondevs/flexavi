<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

use App\Models\User;
use App\Models\Work;
use App\Models\Appointment;
use App\Models\Workable;

class WorkPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @param  array $workable
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        return $user->hasPermissionTo('view works');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Work $work)
    {
        return $user->hasCompanyPermission($work->company_id, 'view works');
    }

    /**
     * Determine whether the user can record models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        if ($role = $user->roles()->first()) {
            return ($role->name == 'owner') || ($role->name == 'employee');
        }

        return false;
    }

    /**
     * Determine whether the user can attach models.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Work  $work
     * @param  mixed $workable
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function attach(User $user, Work $work, $workable)
    {
        if ($work->company_id !== $workable->company_id) {
            return abort(403, 'Cannot use data from another company.');
        }

        if (Workable::isAlreadyAttached($work, $workable)) {
            return abort(422, 'This work has been attached');
        }

        return $user->hasCompanyPermission($work->company_id, 'attach works');
    }

    /**
     * Determine whether the user can attach many models.
     *
     * @param  \App\Models\User  $user
     * @param  mixed  $workable
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function attachMany(User $user, $workable)
    {
        return $user->hasCompanyPermission($workable->company_id, 'attach many works');
    }

    /**
     * Determine whether the user can detach models.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Work  $work
     * @param  mixed $workable
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function detach(User $user, Work $work, $workable)
    {
        if ($work->company_id !== $workable->company_id) {
            return abort(403, 'Cannot use data from another company.');
        }

        return $user->hasCompanyPermission($work->company_id, 'detach works');
    }

    /**
     * Determine whether the user can detach many models.
     *
     * @param  \App\Models\User  $user
     * @param  mixed  $workable
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function detachMany(User $user, $workable)
    {
        return $user->hasCompanyPermission($workable->company_id, 'detach many works');
    }

    /**
     * Determine whether the user can truncate models relationships.
     *
     * @param  \App\Models\User  $user
     * @param  mixed  $workable
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function truncate(User $user, $workable)
    {
        return $user->hasCompanyPermission($workable->company_id, 'truncate works');
    }

    /**
     * Determine whether the user can execute models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function execute(User $user, Work $work, Appointment $appointment)
    {
        if ($work->status > 1 && $work->status < 4) {
            return abort(403,  'Can no longer execute this work');
        }

        if ($work->company_id !== $appointment->company_id) {
            return abort(403, 'Cannot use data from another company.');
        }

        return $user->hasCompanyPermission($appointment->company_id, 'execute works');
    }

    /**
     * Determine whether the user can mark finished models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function markFinish(User $user, Work $work)
    {
        return $user->hasCompanyPermission($work->company_id, 'mark finish works');
    }

    /**
     * Determine whether the user can unrecord models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Work $work)
    {
        if ($work->status > WorkStatus::Created) {
            return abort(403, 'This work can no longer be updated.');
        }

        return $user->hasCompanyPermission($work->company_id, 'update works');
    }

    /**
     * Determine whether the user can unrecord many models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Work $work)
    {
        if ($work->status == 3) {
            return abort(403, 'This work can no longer be deleted');
        }

        return $user->hasCompanyPermission($work->company_id, 'delete works');
    }

    /**
     * Determine whether the user can truncate models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Work $work)
    {
        return $user->hasCompanyPermission($work->company_id, 'restore works');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Cost  $cost
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Work $work)
    {
        return $user->hasCompanyPermission($work->company_id, 'force delete works');
    }
}
