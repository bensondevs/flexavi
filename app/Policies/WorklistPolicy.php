<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

use App\Models\User;
use App\Models\Workday;
use App\Models\Worklist;
use App\Models\Appointment;

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
     * Determine whether the user can attach appointment to the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Worklist  $worklist
     * @return mixed
     */
    public function attachAppointment(User $user, Worklist $worklist, Appointment $appointment)
    {
        if ($worklist->company_id != $appointment->company_id) {
            return abort(403, 'Cannot use data from another company.');
        }

        return $user->hasPermissionTo('attach appointment worklists');
    }

    /**
     * Determine whether the user can attach many appointments to the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Worklist  $worklist
     * @return mixed
     */
    public function attachManyAppointments(User $user, Worklist $worklist, $appointmentIds)
    {
        $unownedAppointments = Appointment::whereIn('id', $appointmentIds)
            ->where('company_id', $worklist->company_id)
            ->count();

        if ($unownedAppointments > 0) {
            return abort(403, 'Cannot use data from another company.');
        }

        return $user->hasPermissionTo('attach many appointments worklists');
    }

    /**
     * Determine whether the user can detach appointment from the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Worklist  $worklist
     * @return mixed
     */
    public function detachAppointment(User $user, Worklist $worklist, Appointment $appointment)
    {
        if ($worklist->company_id != $appointment->company_id) {
            return abort(403, 'Cannot use data from other company.');
        }

        return $user->hasPermissionTo('detach appointment worklists');
    }

    /**
     * Determine whether the user can detach many appointments from the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Worklist  $worklist
     * @return mixed
     */
    public function detachManyAppointments(User $user, Worklist $worklist, $appointmentIds)
    {
        return $user->hasCompanyPermission($worklist->company_id, 'detach appointment worklists.');
    }

    /**
     * Determine whether the user can truncate appointments from the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Worklist  $worklist
     * @return mixed
     */
    public function truncateAppointments(User $user, Worklist $worklist)
    {
        return $user->hasCompanyPermission($worklist->company_id, 'truncate appointments worklists.');
    }

    /**
     * Determine whether the user can process the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Worklist  $worklist
     * @return mixed
     */
    public function process(User $user, Worklist $worklist)
    {
        return $user->hasCompanyPermission($worklist->company_id, 'process worklists');
    }

    /**
     * Determine whether the user can calculate the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Worklist  $worklist
     * @return mixed
     */
    public function calculate(User $user, Worklist $worklist)
    {
        return $user->hasCompanyPermission($worklist->company_id, 'calculate worklists');
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
