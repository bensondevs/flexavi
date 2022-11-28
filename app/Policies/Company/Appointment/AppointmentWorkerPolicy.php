<?php

namespace App\Policies\Company\Appointment;

use App\Models\Appointment\Appointment;
use App\Models\AppointmentWorker;
use App\Models\User\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AppointmentWorkerPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User\User  $user
     * @return mixed
     */
    public function viewAny(User $user, Appointment $appointment)
    {
        return $user->hasCompanyDirectPermission($appointment->company_id, 'view any appointment workers');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User\User  $user
     * @param  \App\Models\AppointmentWorker  $appointmentWorker
     * @return mixed
     */
    public function view(User $user, AppointmentWorker $appointmentWorker)
    {
        return $user->hasCompanyDirectPermission($appointmentWorker->company_id, 'view appointment workers');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User\User  $user
     * @return mixed
     */
    public function create(User $user, Appointment $appointment)
    {
        return $user->hasCompanyDirectPermission($appointment->company_id, 'create appointment workers');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User\User  $user
     * @param  \App\Models\AppointmentWorker  $appointmentWorker
     * @return mixed
     */
    public function update(User $user, AppointmentWorker $appointmentWorker)
    {
        return $user->hasCompanyDirectPermission($appointmentWorker->company_id, 'edit appointment workers');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User\User  $user
     * @param  \App\Models\AppointmentWorker  $appointmentWorker
     * @return mixed
     */
    public function delete(User $user, AppointmentWorker $appointmentWorker)
    {
        return $user->hasCompanyDirectPermission($appointmentWorker->company_id, 'delete appointment workers');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User\User  $user
     * @param  \App\Models\AppointmentWorker  $appointmentWorker
     * @return mixed
     */
    public function restore(User $user, AppointmentWorker $appointmentWorker)
    {
        return $user->hasCompanyDirectPermission($appointmentWorker->company_id, 'restore appointment workers');
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User\User  $user
     * @param  \App\Models\AppointmentWorker  $appointmentWorker
     * @return mixed
     */
    public function forceDelete(User $user, AppointmentWorker $appointmentWorker)
    {
        return $user->hasCompanyDirectPermission($appointmentWorker->company_id, 'force delete appointment workers');
    }
}
