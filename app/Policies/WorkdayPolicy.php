<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

use App\Models\User;
use App\Models\Workday;
use App\Models\Appointmentable;

use App\Enums\Workday\WorkdayStatus;

class WorkdayPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return bool
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
     * @return bool
     */
    public function view(User $user, Workday $workday)
    {
        return $user->hasCompanyPermission($workday->company_id, 'view workdays');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function create(User $user)
    {
        return false;
    }

    /**
     * Determine whether the user can attach appointment to models.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Workday $workday 
     * @param  \App\Models\Appointment  $appointment
     * @return bool
     */
    public function attachAppointment(User $user, Workday $workday, Appointment $appointment)
    {
        if ($workday->company_id !== $appointment->company_id) {
            return abort(403, 'Cannot use data from other company.');
        }

        if (Appointmentable::isAlreadyAttached($appointment, $workday)) {
            return abort(422, 'This appointment has been attached to the workday');
        }

        return $user->hasCompanyPermission($workday->company_id, 'attach appointment workdays');
    }

    /**
     * Determine whether the user can attach many appointments to models.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Workday $workday 
     * @param  array  $appointmentIds
     * @return bool
     */
    public function attachManyAppointments(User $user, Workday $workday)
    {
        return $user->hasCompanyPermission($workday->company_id, 'attach many appointments workdays');
    }

    /**
     * Determine whether the user can detach appointment from models.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Workday $workday 
     * @param  \App\Models\Appointment  $appointment
     * @return bool
     */
    public function detachAppointment(User $user, Workday $workday, Appointment $appointment)
    {
        if ($workday->company_id !== $appointment->company_id) {
            return abort(403, 'Cannot use data from other company.');
        }

        if (! Appointmentable::isAlreadyAttached($appointment, $workday)) {
            return abort(422, 'This appointment has been attached to the workday');
        }

        return $user->hasCompanyPermission($workday->company_id, 'detach appointment workdays');
    }

    /**
     * Determine whether the user can detach appointment from models.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Workday $workday 
     * @param  array  $appointmentIds
     * @return bool
     */
    public function detachManyAppointments(User $user, Workday $workday, array $appointmentIds)
    {
        return $user->hasCompanyPermission($workday->company_id, 'detach many appointments workdays');
    }

    /**
     * Determine whether the user can detach appointment from models.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Workday $workday 
     * @return bool
     */
    public function truncateAppointments(User $user, Workday $workday)
    {
        return $user->hasCompanyPermission($workday->company_id, 'truncate appointments workdays');
    }

    /**
     * Determine whether the user can process models.
     *
     * @param  \App\Models\User  $user
     * @return bool
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
     * @return bool
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
     * @return bool
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
     * @return bool
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
     * @return bool
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
     * @return bool
     */
    public function forceDelete(User $user, Workday $workday)
    {
        return false;
    }
}
