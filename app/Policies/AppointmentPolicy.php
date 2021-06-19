<?php

namespace App\Policies;

use App\Models\Appointment;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AppointmentPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->hasCompanyPermission($appointment->company_id, 'view appointments');
    }

    public function view(User $user, Appointment $appointment)
    {
        return $user->hasCompanyPermission($appointment->company_id, 'view appointments');
    }

    public function create(User $user)
    {
        return $user->hasPermissionTo('create appointments');
    }

    public function update(User $user, Appointment $appointment)
    {
        return $user->hasCompanyPermission($appointment->company_id, 'edit appointments');
    }

    public function cancel(User $user, Appointment $appointment)
    {
        
    }

    public function execute(User $user, Appointment $appointment)
    {
        //
    }

    public function process(User $user, Appointment $appointment)
    {
        //
    }

    public function delete(User $user, Appointment $appointment)
    {
        //
    }

    public function restore(User $user, Appointment $appointment)
    {
        //
    }

    public function forceDelete(User $user, Appointment $appointment)
    {
        //
    }
}
