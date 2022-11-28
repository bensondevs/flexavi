<?php

namespace App\Policies\Company\Company;

use App\Models\{Company\Company, Owner\Owner, User\User};
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class CompanyPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any companies.
     *
     * @param User $user
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        return $user->hasDirectPermissionTwo('view any companies');
    }

    /**
     * Determine whether the user can view company.
     *
     * @param User $user
     * @param Company $company
     * @return bool
     */
    public function view(User $user, Company $company): bool
    {
        return $user->hasCompanyDirectPermission($company, 'view companies');
    }

    /**
     * Determine whether the user can register company.
     *
     * @param User $user
     * @param Owner $owner
     * @return Response|bool
     */
    public function register(User $user, Owner $owner): Response|bool
    {
        if (!$this->create($user)) {
            return abort(403, 'This user cannot create new company.');
        }

        if ($owner->company()->exists()) {
            return abort(403, 'This owner has already has a company. ');
        }

        return $user->hasDirectPermissionTwo('register companies');
    }

    /**
     * Determine whether the user can create company.
     *
     * @param User $user
     * @return Response|bool
     */
    public function create(User $user): Response|bool
    {
        if (!$user->hasRole('owner')) {
            return abort(403, 'This user has no owner role.');
        }

        if (!$user->owner()->exists()) {
            return abort(403, 'This user has no owner profile.');
        }

        return $user->hasDirectPermissionTwo('register companies');
    }

    /**
     * Determine whether the user can update company.
     *
     * @param User $user
     * @param Company $company
     * @return bool
     */
    public function update(User $user, Company $company): bool
    {
        return $user->hasCompanyDirectPermission($company->id, 'edit companies');
    }

    /**
     * Determine whether the user can delete company.
     *
     * @param User $user
     * @param Company $company
     * @return bool
     */
    public function delete(User $user, Company $company): bool
    {
        return $user->hasCompanyDirectPermission($company->id, 'delete companies');
    }

    /**
     * Determine whether the user can restore company.
     *
     * @param User $user
     * @param Company $company
     * @return Response|bool
     */
    public function restore(User $user, Company $company): Response|bool
    {
        return $user->hasCompanyDirectPermission($company->id, 'restore companies');
    }

    /**
     * Determine whether the user can force delete company.
     *
     * @param User $user
     * @param Company $company
     * @return Response|bool
     */
    public function forceDelete(User $user, Company $company): Response|bool
    {
        return $user->hasCompanyDirectPermission($company->id, 'force delete companies');
    }
}
