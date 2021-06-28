<?php

namespace App\Policies;

use App\Models\Company;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CompanyPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->hasRole('admin');
    }

    public function view(User $user, Company $company)
    {
        return $user->hasCompanyPermission($company->id, 'manage companies');
    }

    public function create(User $user)
    {
        return (! $user->owner) && $user->hasRole('owner');
    }

    public function update(User $user, Company $company)
    {
        return $user->hasCompanyPermission($company->id, 'manage companies');
    }

    public function delete(User $user, Company $company)
    {
        return $user->hasRole('admin');
    }

    public function restore(User $user, Company $company)
    {
        return $user->hasRole('admin');
    }

    public function forceDelete(User $user, Company $company)
    {
        return $user->hasRole('admin');
    }
}
