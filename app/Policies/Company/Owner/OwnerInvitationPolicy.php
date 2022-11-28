<?php

namespace App\Policies\Company\Owner;

use App\Models\{Owner\OwnerInvitation, User\User};
use Illuminate\Auth\Access\HandlesAuthorization;

class OwnerInvitationPolicy
{
    use HandlesAuthorization;

    /**
     * Determine the user can view any invitation
     *
     * @param User $user
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        return $user->hasDirectPermissionTwo('view any invitation owners');
    }

    /**
     * Determine the user can view invitation
     *
     * @param User $user
     * @param OwnerInvitation $ownerInvitation
     * @return bool
     */
    public function view(User $user, OwnerInvitation $ownerInvitation): bool
    {
        return $user->hasCompanyDirectPermission($ownerInvitation->company, 'view pending invitation owner');
    }

    /**
     * Determine the user can cancel invitation
     *
     * @param User $user
     * @param OwnerInvitation $ownerInvitation
     * @return bool
     */
    public function cancel(User $user, OwnerInvitation $ownerInvitation): bool
    {
        return $user->hasCompanyDirectPermission($ownerInvitation->company, 'cancel owner invitations');
    }

    /**
     * Determine the user can invite owner
     *
     * @param User $user
     * @return bool
     */
    public function store(User $user): bool
    {
        return $user->hasDirectPermissionTwo('send owner register invitation');
    }
}
