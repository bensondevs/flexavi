<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

use App\Models\User;
use App\Models\Work;
use App\Models\Appointment;

class WorkPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user, $workAttachable)
    {
        return $user->hasCompanyPermission($workAttachable->company_id, 'view works');
    }

    public function viewAnyAppointment(User $user, Appointment $appointment)
    {
        return $user->hasCompanyPermission($appointment->company_id, 'view appointment works');
    }

    public function view(User $user, Work $work)
    {
        return $user->hasCompanyPermission($work->company_id, 'view works');
    }

    public function create(User $user)
    {
        if ($role = $user->roles()->first()) {
            return ($role->name == 'owner') || ($role->name == 'employee');
        }

        return false;
    }

    public function update(User $user, Work $work)
    {
        return $user->hasCompanyPermission($work->company_id, 'update works');
    }

    public function delete(User $user, Work $work)
    {
        return $user->hasCompanyPermission($work->company_id, 'delete works');
    }

    public function restore(User $user, Work $work)
    {
        return $user->hasCompanyPermission($work->company_id, 'restore works');
    }

    public function forceDelete(User $user, Work $work)
    {
        return $user->hasCompanyPermission($work->company_id, 'force delete works');
    }
}
