<?php

namespace App\Services\Permission;

use App\Enums\Role;
use App\Models\Company\Company;
use App\Models\Owner\OwnerInvitation;
use App\Models\Permission\Permission;
use App\Models\User\User;

/**
 * @see \Tests\Unit\Services\Permission\PermissionService\PermissionServiceTest
 *      To the class unit tester class.
 */
class PermissionService
{
    /**
     * Set direct permission into user from owner invitation
     *
     * @param OwnerInvitation $ownerInvitation
     * @return void
     */
    public function setPermissionFromOwnerInvitation(OwnerInvitation $ownerInvitation): void
    {
        $user = User::whereRegistrationCode($ownerInvitation->registration_code)->first();
        $permissionsName = Permission::whereIn('id', $ownerInvitation->permissions)
            ->get()
            ->pluck('name')
            ->toArray();
        $user->givePermissionTo($permissionsName);
    }

    /**
     * Determine whether a user is within specified company.
     *
     * @param User $user
     * @param Company $company
     * @return bool
     * @see \Tests\Unit\Services\Permission\PermissionService\HasAccessInCompanyTest
     *      To the method unit tester class.
     */
    public function hasAccessInCompany(User $user, Company $company): bool
    {
        // Refresh the user to ensure the user instance is the latest record
        $user->refresh();

        // Get the user role instance record
        $userRole = $user->fresh()->roles->first();
        if (is_null($userRole)) {
            abort(403, 'You do not have any role assigned.');
        }
        if (!in_array($userRole->name, [Role::Admin, Role::Owner, Role::Employee])) {
            abort(
                403,
                'There is a mistake in role assignment. Your role: ' . $userRole->name
            );
        }

        // Always allow administrator user to access any kinds of company
        if ($userRole->name === Role::Admin) {
            return true;
        }

        // Get user company record instance
        $userCompany = $user->company;
        if (!isset($userCompany->id)) {
            abort(403, 'Your company record is not found.');
        }

        // Ensure the user is belongs to the specified company
        if ($userCompany->id !== $company->id) {
            abort(403, 'You are not belong to this company.');
        }

        return true;
    }
}
