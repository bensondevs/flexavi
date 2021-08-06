<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

use App\Models\User;
use App\Models\Appointment;
use App\Models\AppointmentCost;

class AppointmentCostPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user, Appointment $appointment)
    {
        return $user->hasCompanyPermission($appointment->company_id, 'view any appointment costs');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\AppointmentCost  $appointmentCost
     * @return mixed
     */
    public function view(User $user, AppointmentCost $appointmentCost)
    {
        return $user->hasCompanyPermission($appointmentCost->company_id, 'view appointment costs');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user, Appointment $appointment)
    {
        return $user->hasCompanyPermission($appointment->company_id, 'create appointment costs');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\AppointmentCost  $appointmentCost
     * @return mixed
     */
    public function update(User $user, AppointmentCost $appointmentCost)
    {
        return $user->hasCompanyPermission($appointmentCost->company_id, 'edit appointment costs');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\AppointmentCost  $appointmentCost
     * @return mixed
     */
    public function delete(User $user, AppointmentCost $appointmentCost)
    {
        return $user->hasCompanyPermission($appointmentCost->company_id, 'delete appointment costs');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\AppointmentCost  $appointmentCost
     * @return mixed
     */
    public function restore(User $user, AppointmentCost $appointmentCost)
    {
        return $user->hasCompanyPermission($appointmentCost->company_id, 'restore appointment costs');
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\AppointmentCost  $appointmentCost
     * @return mixed
     */
    public function forceDelete(User $user, AppointmentCost $appointmentCost)
    {
        return $user->hasCompanyPermission($appointmentCost->company_id, 'force delete appointment costs');
    }
}
