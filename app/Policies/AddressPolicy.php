<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

use App\Models\User;
use App\Models\Address;
use App\Models\Employee;

class AddressPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return true;
    }

    public function viewAnyEmployee(User $user, Employee $employee)
    {
        return $user->hasCompanyPermission($employee->company_id, 'view any addresses');
    }

    public function create(User $user)
    {
        return true;
    }

    public function update(User $user, Address $address)
    {
        if ($user->id == $address->user_id) return true;

        return $user->hasCompanyPermission($address->company_id, 'edit addresses');
    }

    public function delete(User $user, Address $address)
    {
        if ($user->id == $address->user_id) return true;

        return $user->hasCompanyPermission($address->company_id, 'delete addresses');
    }

    public function restore(User $user, Address $address)
    {
        if ($user->id == $address->user_id) return true;

        return $user->hasCompanyPermission($address->company_id, 'restore addresses');
    }

    public function forceDelete(User $user, Address $address)
    {
        if ($user->id == $address->user_id) return true;

        return $user->hasCompanyPermission($address->company_id, 'force delete addresses');
    }
}
