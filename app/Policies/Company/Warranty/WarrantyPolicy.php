<?php

namespace App\Policies\Company\Warranty;

use App\Models\{Appointment\Appointment, Employee\Employee, User\User, Warranty\Warranty};
use Illuminate\Auth\Access\HandlesAuthorization;

class WarrantyPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        return $user->hasDirectPermissionTwo('view any warranties');
    }

    /**
     * Determine whether the user can view any models under the employee.
     *
     * @param  \App\Models\User\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAnyEmployee(User $user, Employee $employee)
    {
        return $user->hasCompanyDirectPermission($employee->company_id, 'view any warranties');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User\User  $user
     * @param  \App\Models\Warranty\Warranty  $warranty
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Warranty $warranty)
    {
        return $user->hasCompanyDirectPermission($warranty->company_id, 'view warranties');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user, Appointment $appointment)
    {
        if (!$user->hasCompanyDirectPermission($appointment->company_id, 'create warranties')) {
            return abort(403, 'Cannot assign warranty at this appointment.');
        }
        return true;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User\User  $user
     * @param  \App\Models\Warranty\Warranty  $warranty
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Warranty $warranty)
    {
        return $user->hasCompanyDirectPermission($warranty->company_id, 'edit warranties');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User\User  $user
     * @param  \App\Models\Warranty\Warranty  $warranty
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Warranty $warranty)
    {
        return $user->hasCompanyDirectPermission($warranty->company_id, 'delete warranties');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User\User  $user
     * @param  \App\Models\Warranty\Warranty  $warranty
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Warranty $warranty)
    {
        return $user->hasCompanyDirectPermission($warranty->company_id, 'restore warranties');
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User\User  $user
     * @param  \App\Models\Warranty\Warranty  $warranty
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Warranty $warranty)
    {
        return $user->hasCompanyDirectPermission($warranty->company_id, 'force delete warranties');
    }
}
