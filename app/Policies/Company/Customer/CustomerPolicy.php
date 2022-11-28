<?php

namespace App\Policies\Company\Customer;

use App\Models\Customer\Customer;
use App\Models\User\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CustomerPolicy
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
        return $user->hasDirectPermissionTwo('view any customers');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param User $user
     * @param Customer $customer
     * @return bool
     */
    public function view(User $user, Customer $customer): bool
    {
        return $user->hasCompanyDirectPermission($customer->company_id, 'view customers');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param User $user
     * @return bool
     */
    public function create(User $user): bool
    {
        return $user->hasDirectPermissionTwo('create customers');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param User $user
     * @param Customer $customer
     * @return bool
     */
    public function update(User $user, Customer $customer): bool
    {
        return $user->hasCompanyDirectPermission($customer->company_id, 'edit customers');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param User $user
     * @param Customer $customer
     * @return bool
     */
    public function delete(User $user, Customer $customer): bool
    {
        return $user->hasCompanyDirectPermission($customer->company_id, 'delete customers');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param User $user
     * @param Customer $customer
     * @return bool
     */
    public function restore(User $user, Customer $customer): bool
    {
        return $user->hasCompanyDirectPermission($customer->company_id, 'restore customers');
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param User $user
     * @param Customer $customer
     * @return bool
     */
    public function forceDelete(User $user, Customer $customer): bool
    {
        return $user->hasCompanyDirectPermission($customer->company_id, 'force delete customers');
    }

    /**
     * Determine whether the user can view the city of customers
     *
     * @param User $user
     * @return bool
     */
    public function viewCityOfCustomer(User $user): bool
    {
        /**
         * @note This is useless, because to see customers is directly
         *          able to see the cities
         */
        // return $user->hasDirectPermissionTwo('view city of customers');

        return $user->hasDirectPermissionTwo('view any customers');
    }
}
