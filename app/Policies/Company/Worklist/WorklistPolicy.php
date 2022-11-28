<?php

namespace App\Policies\Company\Worklist;

use App\Models\Appointment\Appointment;
use App\Models\Employee\Employee;
use App\Models\User\User;
use App\Models\Workday\Workday;
use App\Models\Worklist\Worklist;
use Illuminate\Auth\Access\HandlesAuthorization;

class WorklistPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param User $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $user->hasDirectPermissionTwo('view any worklists');
    }

    /**
     * Determine whether the user can move appointment to other worklist.
     *
     * @param User $user
     * @param mixed $fromWorklist
     * @param mixed $toWorklist
     * @param \App\Models\Appointment\Appointment $appointment
     * @return mixed
     */
    public function moveAppointment(User $user, $fromWorklist, $toWorklist, Appointment $appointment)
    {
        if (!is_null($fromWorklist)) {
            if ($fromWorklist->company_id != $appointment->company_id) {
                return abort(403, 'Cannot use data from another company.');
            }
        }

        if (!is_null($toWorklist)) {
            if ($toWorklist->company_id != $appointment->company_id) {
                return abort(403, 'Cannot use data from another company.');
            }
        }

        return $user->hasDirectPermissionTwo('move appointment worklists');
    }

    /**
     * Determine whether the user can view any models under workdays.
     *
     * @param User $user
     * @return mixed
     */
    public function viewAnyWorkday(User $user, Workday $workday)
    {
        return $user->hasCompanyDirectPermission($workday->company_id, 'view any worklists');
    }

    /**
     * Determine whether the user can view any models under employee.
     *
     * @param User $user
     * @return mixed
     */
    public function viewAnyEmployee(User $user, Employee $employee)
    {
        return $user->hasCompanyDirectPermission($employee->company_id, 'view any worklists');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param User $user
     * @param Worklist $worklist
     * @return mixed
     */
    public function view(User $user, Worklist $worklist)
    {
        return $user->hasCompanyDirectPermission($worklist->company_id, 'view worklists');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param User $user
     * @return mixed
     */
    public function create(User $user, Workday $workday)
    {
        return $user->hasCompanyDirectPermission($workday->company_id, 'create worklists');
    }

    /**
     * Determine whether the user can attach appointment to the model.
     *
     * @param User $user
     * @param Worklist $worklist
     * @return mixed
     */
    public function attachAppointment(User $user, Worklist $worklist, Appointment $appointment)
    {
        if ($worklist->company_id != $appointment->company_id) {
            return abort(403, 'Cannot use data from another company.');
        }

        return $user->hasDirectPermissionTwo('attach appointment worklists');
    }

    /**
     * Determine whether the user can attach many appointments to the model.
     *
     * @param User $user
     * @param Worklist $worklist
     * @return mixed
     */
    public function attachManyAppointments(User $user, Worklist $worklist, $appointmentIds)
    {
        $unownedAppointments = Appointment::whereIn('id', $appointmentIds)
            ->where('company_id', '!=', $worklist->company_id)
            ->count();

        if ($unownedAppointments > 0) {
            return abort(403, 'Cannot use data from another company.');
        }

        return $user->hasDirectPermissionTwo('attach many appointments worklists');
    }

    /**
     * Determine whether the user can detach appointment from the model.
     *
     * @param User $user
     * @param Worklist $worklist
     * @return mixed
     */
    public function detachAppointment(User $user, Worklist $worklist, Appointment $appointment)
    {
        if ($worklist->company_id != $appointment->company_id) {
            return abort(403, 'Cannot use data from other company.');
        }

        return $user->hasDirectPermissionTwo('detach appointment worklists');
    }

    /**
     * Determine whether the user can detach many appointments from the model.
     *
     * @param User $user
     * @param Worklist $worklist
     * @return mixed
     */
    public function detachManyAppointments(User $user, Worklist $worklist, $appointmentIds)
    {
        return $user->hasCompanyDirectPermission($worklist->company_id, 'detach many appointments worklists');
    }

    /**
     * Determine whether the user can truncate appointments from the model.
     *
     * @param User $user
     * @param Worklist $worklist
     * @return mixed
     */
    public function truncateAppointments(User $user, Worklist $worklist)
    {
        return $user->hasCompanyDirectPermission($worklist->company_id, 'truncate appointments worklists');
    }

    /**
     * Determine whether the user can process the model.
     *
     * @param User $user
     * @param Worklist $worklist
     * @return mixed
     */
    public function process(User $user, Worklist $worklist)
    {
        return $user->hasCompanyDirectPermission($worklist->company_id, 'process worklists');
    }

    /**
     * Determine whether the user can calculate the model.
     *
     * @param User $user
     * @param Worklist $worklist
     * @return mixed
     */
    public function calculate(User $user, Worklist $worklist)
    {
        return $user->hasCompanyDirectPermission($worklist->company_id, 'calculate worklists');
    }

    /**
     * Determine whether the user can sorting route the model.
     *
     * @param User $user
     * @param Worklist $worklist
     * @return mixed
     */
    public function sortingRoute(User $user, Worklist $worklist)
    {
        return $user->hasCompanyDirectPermission($worklist->company_id, 'sorting route worklists');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param User $user
     * @param Worklist $worklist
     * @return mixed
     */
    public function update(User $user, Worklist $worklist)
    {
        return $user->hasCompanyDirectPermission($worklist->company_id, 'edit worklists');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param User $user
     * @param Worklist $worklist
     * @return mixed
     */
    public function delete(User $user, Worklist $worklist)
    {
        return $user->hasCompanyDirectPermission($worklist->company_id, 'delete worklists');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param User $user
     * @param Worklist $worklist
     * @return mixed
     */
    public function restore(User $user, Worklist $worklist)
    {
        return $user->hasCompanyDirectPermission($worklist->company_id, 'restore worklists');
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param User $user
     * @param Worklist $worklist
     * @return mixed
     */
    public function forceDelete(User $user, Worklist $worklist)
    {
        return $user->hasCompanyDirectPermission($worklist->company_id, 'force delete worklists');
    }
}
