<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

use App\Models\{ Company, User, Owner };

class CompanyPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any companies.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can view company.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Company  $company
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Company $company)
    {
        return $user->hasCompanyPermission($company->id, 'manage companies');
    }

    /**
     * Determine whether the user can create company.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        if (! $user->hasRole('owner')) {
            return abort(403, 'This user has no owner role.');
        }

        if (! $user->owner()->exists()) {
            return abort(403, 'This user has no owner profile.');
        }

        return true;
    }

    /**
     * Determine whether the user can register company.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Owner  $owner
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function register(User $user, Owner $owner)
    {
        if (! $this->create($user)) {
            return abort(403, 'This user cannot create new company.');
        }

        if ($owner->company()->exists()) {
            return abort(403, 'This owner has already has a company. ');
        }

        return true;
    }

    /**
     * Determine whether the user can update company.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Company  $company
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Company $company)
    {
        return $user->hasCompanyPermission($company->id, 'edit companies');
    }

    /**
     * Determine whether the user can delete company.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Company  $company
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Company $company)
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can restore company.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Company  $company
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Company $company)
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can force delete company.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Company  $company
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Company $company)
    {
        return $user->hasRole('admin');
    }
}
