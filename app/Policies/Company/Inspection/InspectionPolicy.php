<?php

namespace App\Policies\Company\Inspection;

use App\Models\{Customer\Customer, Employee\Employee, Inspection\Inspection, User\User};
use Illuminate\Auth\Access\HandlesAuthorization;

class InspectionPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any inspection.
     *
     * @param  \App\Models\User\User  $user
     * @return bool
     */
    public function viewAny(User $user)
    {
        return $user->hasDirectPermissionTwo('view inspections');
    }

    /**
     * Determine whether the user can view any customer inspection.
     *
     * @param  \App\Models\User\User  $user
     * @param  \App\Models\Customer\Customer $customer
     * @return bool
     */
    public function viewAnyCustomer(User $user, Customer $customer)
    {
        return $user->hasCompanyDirectPermission($customer->company_id, 'view inspections');
    }

    /**
     * Determine whether the user can view any employee inspection.
     *
     * @param  \App\Models\User\User  $user
     * @param  \App\Models\Employee\Employee $employee
     * @return bool
     */
    public function viewAnyEmployee(User $user, Employee $employee)
    {
        return $user->hasCompanyDirectPermission($employee->company_id, 'view inspections');
    }

    /**
     * Determine whether the user can view inspection.
     *
     * @param  \App\Models\User\User  $user
     * @param  \App\Models\Inspection\Inspection $inspection
     * @return bool
     */
    public function view(User $user, Inspection $inspection)
    {
        return $user->hasCompanyDirectPermission($inspection->company_id, 'view inspections');
    }

    /**
     * Determine whether the user can create inspection.
     *
     * @param  \App\Models\User\User  $user
     * @param  \App\Models\Customer\Customer $inspection
     * @return bool
     */
    public function create(User $user)
    {
        return $user->hasDirectPermissionTwo('create inspections');
    }

    /**
     * Determine whether the user can view inspection.
     *
     * @param  \App\Models\User\User  $user
     * @param  \App\Models\Customer\Customer $customer
     * @return bool
     */
    public function update(User $user, Inspection $inspection, Customer $customer)
    {
        if ($inspection->company_id != $customer->company_id) {
            return abort(403, 'Cannot create inspection using other company data.');
        }

        if (!$user->hasCompanyDirectPermission($inspection->company_id, 'edit inspections')) {
            return abort(403, 'You don\'t have permission to update inspections');
        }

        return true;
    }

    /**
     * Determine whether the user can delete inspection.
     *
     * @param  \App\Models\User\User  $user
     * @param  \App\Models\Inspection\Inspection $inspection
     * @return bool
     */
    public function delete(User $user, Inspection $inspection)
    {
        return $user->hasCompanyDirectPermission($inspection->company_id, 'delete inspections');
    }

    /**
     * Determine whether the user can restore inspection.
     *
     * @param  \App\Models\User\User  $user
     * @param  \App\Models\Inspection\Inspection $inspection
     * @return bool
     */
    public function restore(User $user, Inspection $inspection)
    {
        return $user->hasCompanyDirectPermission($inspection->company_id, 'restore inspections');
    }

    /**
     * Determine whether the user can force delete inspection.
     *
     * @param  \App\Models\User\User  $user
     * @param  \App\Models\Inspection\Inspection $inspection
     * @return bool
     */
    public function forceDelete(User $user, Inspection $inspection)
    {
        return $user->hasCompanyDirectPermission($inspection->company_id, 'force delete inspections');
    }
}
