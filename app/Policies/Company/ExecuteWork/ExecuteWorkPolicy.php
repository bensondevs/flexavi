<?php

namespace App\Policies\Company\ExecuteWork;

use App\Models\Customer\Customer;
use App\Models\ExecuteWork\ExecuteWork;
use App\Models\User\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ExecuteWorkPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->hasDirectPermissionTwo('view any execute works');
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
        return $user->hasCompanyDirectPermission($customer->company_id, 'view execute works');
    }

    /**
     * Determine whether the user can view inspection.
     *
     * @param  \App\Models\User\User  $user
     * @param  \App\Models\ExecuteWork\ExecuteWork $executeWork
     * @return bool
     */
    public function view(User $user, ExecuteWork $executeWork)
    {
        return $user->hasCompanyDirectPermission($executeWork->company_id, 'view execute works');
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
        return $user->hasDirectPermissionTwo('create execute works');
    }

    /**
     * Determine whether the user can view inspection.
     *
     * @param  \App\Models\User\User  $user
     * @param  \App\Models\ExecuteWork\ExecuteWork  $executeWork
     * @param  \App\Models\Customer\Customer $customer
     * @return bool
     */
    public function update(User $user, ExecuteWork $executeWork, Customer $customer)
    {
        if ($executeWork->company_id != $customer->company_id) {
            return abort(403, 'Cannot create execute work using other company data.');
        }

        if (!$user->hasCompanyDirectPermission($executeWork->company_id, 'edit execute works')) {
            return abort(403, 'You don\'t have permission to update execute works');
        }

        return true;
    }

    public function delete(User $user, ExecuteWork $executeWork)
    {
        return $user->hasCompanyDirectPermission($executeWork->company_id, 'delete execute works');
    }

    public function restore(User $user, ExecuteWork $executeWork)
    {
        return $user->hasCompanyDirectPermission($executeWork->company_id, 'restore execute works');
    }
}
