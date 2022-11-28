<?php

namespace App\Policies\Company\Appointment;

use App\Enums\SubAppointment\SubAppointmentStatus;
use App\Models\Appointment\Appointment;
use App\Models\Appointment\SubAppointment;
use App\Models\User\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SubAppointmentPolicy
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
        return $user->hasCompanyDirectPermission($appointment->company_id, 'view any sub appointments');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User\User  $user
     * @param  \App\Models\Appointment\SubAppointment  $subAppointment
     * @return mixed
     */
    public function view(User $user, SubAppointment $subAppointment)
    {
        return $user->hasCompanyDirectPermission($subAppointment->company_id, 'view sub appointments');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User\User  $user
     * @return mixed
     */
    public function create(User $user, Appointment $appointment)
    {
        return $user->hasCompanyDirectPermission($appointment->company_id, 'create sub appointments');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User\User  $user
     * @param  \App\Models\Appointment\SubAppointment  $subAppointment
     * @return mixed
     */
    public function update(User $user, SubAppointment $subAppointment)
    {
        if ($subAppointment->status > SubAppointmentStatus::Created) {
            return abort(422, 'Sub-appointment can no longer be updated.');
        }

        return $user->hasCompanyDirectPermission($subAppointment->company_id, 'edit sub appointments');
    }

    /**
     * Determine whether the user can execute the model.
     *
     * @param  \App\Models\User\User  $user
     * @param  \App\Models\Appointment\SubAppointment  $subAppointment
     * @return mixed
     */
    public function execute(User $user, SubAppointment $subAppointment)
    {
        if ($subAppointment->status != SubAppointmentStatus::Created) {
            return abort(422, 'Sub-appointment can no longer be executed.');
        }

        return $user->hasCompanyDirectPermission($subAppointment->company_id, 'execute sub appointments');
    }

    /**
     * Determine whether the user can process the model.
     *
     * @param  \App\Models\User\User  $user
     * @param  \App\Models\Appointment\SubAppointment  $subAppointment
     * @return mixed
     */
    public function process(User $user, SubAppointment $subAppointment)
    {
        if ($subAppointment->status != SubAppointmentStatus::InProcess) {
            return abort(422, 'Sub-appointment can no longer be processed.');
        }

        return $user->hasCompanyDirectPermission($subAppointment->company_id, 'process sub appointments');
    }

    /**
     * Determine whether the user can reschedule the model.
     *
     * @param  \App\Models\User\User  $user
     * @param  \App\Models\Appointment\SubAppointment  $subAppointment
     * @return mixed
     */
    public function reschedule(User $user, SubAppointment $subAppointment)
    {
        if ($subAppointment->rescheduled_sub_appointment_id) {
            return abort(422, 'This sub-appointment is already rescheduled.');
        }

        return $user->hasCompanyDirectPermission($subAppointment->company_id, 'reschedule sub appointments');
    }

    /**
     * Determine whether the user can cancel the model.
     *
     * @param  \App\Models\User\User  $user
     * @param  \App\Models\Appointment\SubAppointment  $subAppointment
     * @return mixed
     */
    public function cancel(User $user, SubAppointment $subAppointment)
    {
        if ($subAppointment->status >= SubAppointmentStatus::Processed) {
            return abort(422, 'This sub-appointment can no longer be cancelled.');
        }

        return $user->hasCompanyDirectPermission($subAppointment->company_id, 'cancel sub appointments');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User\User  $user
     * @param  \App\Models\Appointment\SubAppointment  $subAppointment
     * @return mixed
     */
    public function delete(User $user, SubAppointment $subAppointment)
    {
        return $user->hasCompanyDirectPermission($subAppointment->company_id, 'delete sub appointments');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User\User  $user
     * @param  \App\Models\Appointment\SubAppointment  $subAppointment
     * @return mixed
     */
    public function restore(User $user, SubAppointment $subAppointment)
    {
        return $user->hasCompanyDirectPermission($subAppointment->company_id, 'restore sub appointments');
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User\User  $user
     * @param  \App\Models\Appointment\SubAppointment  $subAppointment
     * @return mixed
     */
    public function forceDelete(User $user, SubAppointment $subAppointment)
    {
        return $user->hasCompanyDirectPermission($subAppointment->company_id, 'force delete sub appointmeents');
    }
}
