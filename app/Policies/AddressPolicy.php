<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

use App\Models\User;
use App\Models\Address;
use App\Models\Employee;

class AddressPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user, $addressable = null)
    {
        if (! $addressable) {
            return true;
        }

        return $user->hasCompanyPermission($addressable->company_id, 'view any addresses');
    }

    public function view(User $user, Address $address)
    {
        return $user->hasCompanyPermission($address->addressable->company_id, 'view addresses');
    }

    public function create(User $user, $addressable)
    {
        return $user->hasCompanyPermission($addressable->company_id, 'create addresses');
    }

    public function update(User $user, Address $address, $addressable)
    {
        return $user->hasCompanyPermission($addressable->company_id, 'edit addresses');
    }

    public function delete(User $user, Address $address, $addressable)
    {
        if ($user->id == $addressable->user_id) {
            return true;
        }

        return $user->hasCompanyPermission($addressable->company_id, 'delete addresses');
    }

    public function restore(User $user, Address $address, $addressable)
    {
        if ($user->id == $addressable->user_id) {
            return true;
        }

        return $user->hasCompanyPermission($addressable->company_id, 'restore addresses');
    }

    public function forceDelete(User $user, Address $address, $addressable)
    {
        if ($address->addressable_id !== $addressable->id) {
            return abort(422, 'Invalid addressable ID.');
        }

        if ($user->id == $addressable->user_id) {
            return true;
        }

        return $user->hasCompanyPermission($addressable->company_id, 'force delete addresses');
    }
}
