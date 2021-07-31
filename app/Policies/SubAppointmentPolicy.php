<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

use App\Models\User;
use App\Models\Appointment;
use App\Models\SubAppointment;

class SubAppointmentPolicy
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
        return $user->hasCompanyPermission($appointment->coompany_id, 'view any sub appointmeents');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\SubAppointment  $subAppointment
     * @return mixed
     */
    public function view(User $user, SubAppointment $subAppointment)
    {
        return $user->hasCompanyPermission($subAppointment->company_id, 'view sub appointments');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user, Appointment $appointment)
    {
        return $user->hasCompanyPermission($appointment->company_id, 'create sub appointments');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\SubAppointment  $subAppointment
     * @return mixed
     */
    public function update(User $user, SubAppointment $subAppointment)
    {
        return $user->hasCompanyPermission($subAppointment->company_id, 'edit sub appointments');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\SubAppointment  $subAppointment
     * @return mixed
     */
    public function delete(User $user, SubAppointment $subAppointment)
    {
        return $user->hasCompanyPermission($subAppointment->company_id, 'delete sub appointments');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\SubAppointment  $subAppointment
     * @return mixed
     */
    public function restore(User $user, SubAppointment $subAppointment)
    {
        return $user->hasCompanyPermission($subAppointment->company_id, 'restore sub appointments');
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\SubAppointment  $subAppointment
     * @return mixed
     */
    public function forceDelete(User $user, SubAppointment $subAppointment)
    {
        return $user->hasCompanyPermission($subAppointment->company_id, 'force delete sub appointmeents');
    }
}
