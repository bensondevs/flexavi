<?php

namespace App\Policies\Company\Employee;

use App\Models\Employee\Employee;
use App\Models\User\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class EmployeePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param User $user
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        return $user->hasDirectPermissionTwo('view any employees');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param User $user
     * @param Employee $employee
     * @return bool
     */
    public function view(User $user, Employee $employee): bool
    {
        return $user->hasCompanyDirectPermission($employee->company_id, 'view employees');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param User $user
     * @return bool
     */
    public function create(User $user): bool
    {
        return $user->hasDirectPermissionTwo('create employees');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param User $user
     * @param Employee $employee
     * @return bool
     */
    public function update(User $user, Employee $employee): bool
    {
        return $user->hasCompanyDirectPermission($employee->company_id, 'edit employees');
    }

    /**
     * Determine whether the user can set image.
     *
     * @param User $user
     * @param Employee $employee
     * @return bool
     */
    public function setImage(User $user, Employee $employee): bool
    {
        return $user->hasCompanyDirectPermission($employee->company_id, 'set image employees');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param User $user
     * @param Employee $employee
     * @return bool
     */
    public function delete(User $user, Employee $employee): bool
    {
        return $user->hasCompanyDirectPermission($employee->company_id, 'delete employees');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param User $user
     * @param Employee $employee
     * @return bool
     */
    public function restore(User $user, Employee $employee): bool
    {
        return $user->hasCompanyDirectPermission($employee->company_id, 'restore employees');
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param User $user
     * @param Employee $employee
     * @return bool
     */
    public function forceDelete(User $user, Employee $employee): bool
    {
        return $user->hasCompanyDirectPermission($employee->company_id, 'force delete employees');
    }
}
