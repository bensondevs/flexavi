<?php

namespace App\Policies\Company\Address;

use App\Models\Address\Address;
use App\Models\User\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AddressPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any Address.
     *
     * @param User $user
     * @param mixed|null $addressable |null
     * @return bool
     */
    public function viewAny(User $user, mixed $addressable = null): bool
    {
        if (!$addressable) {
            return true;
        }

        return $user->hasCompanyDirectPermission($addressable->company_id, 'view any addresses');
    }

    /**
     * Determine whether the user can view autocomplete address by pro6pp service.
     *
     * @param User $user
     * @return bool
     */
    public function pro6ppAutocompleteAddress(User $user): bool
    {
        return $user->hasDirectPermissionTwo('pro6pp autocomplete address');
    }

    /**
     * Determine whether the user can view address.
     *
     * @param User $user
     * @param Address $address
     * @return bool
     */
    public function view(User $user, Address $address): bool
    {
        return $user->hasCompanyDirectPermission($address->addressable->company_id, 'view addresses');
    }

    /**
     * Determine whether the user can create address.
     *
     * @param User $user
     * @param mixed $addressable
     * @return bool
     */
    public function create(User $user, mixed $addressable): bool
    {
        return $user->hasCompanyDirectPermission($addressable->company_id, 'create addresses');
    }

    /**
     * Determine whether the user can update address.
     *
     * @param User $user
     * @param Address $address
     * @param mixed $addressable
     * @return bool
     */
    public function update(User $user, Address $address, mixed $addressable): bool
    {
        return $user->hasCompanyDirectPermission($addressable->company_id, 'edit addresses');
    }

    /**
     * Determine whether the user can delete address.
     *
     * @param User $user
     * @param Address $address
     * @param mixed $addressable
     * @return bool
     */
    public function delete(User $user, Address $address, mixed $addressable): bool
    {
        if ($user->id == $addressable->user_id) {
            return true;
        }

        return $user->hasCompanyDirectPermission($addressable->company_id, 'delete addresses');
    }

    /**
     * Determine whether the user can view restore address.
     *
     * @param User $user
     * @param Address $address
     * @param mixed $addressable
     * @return bool
     */
    public function restore(User $user, Address $address, mixed $addressable): bool
    {
        if ($user->id == $addressable->user_id) {
            return true;
        }

        return $user->hasCompanyDirectPermission($addressable->company_id, 'restore addresses');
    }

    /**
     * Determine whether the user can hard delete address.
     *
     * @param User $user
     * @param Address $address
     * @param mixed $addressable
     * @return bool
     */
    public function forceDelete(User $user, Address $address, mixed $addressable): bool
    {
        if ($address->addressable_id !== $addressable->id) {
            return abort(422, 'Invalid addressable ID.');
        }

        if ($user->id == $addressable->user_id) {
            return true;
        }

        return $user->hasCompanyDirectPermission($addressable->company_id, 'force delete addresses');
    }
}
