<?php

namespace App\Policies\Company\Invitation;

use App\Models\User\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PendingInvitationPolicy
{
    use HandlesAuthorization;

    public function viewPendingInvitationOwners(User $user)
    {
        return $user->hasDirectPermissionTwo('view pending invitation owners');
    }

    public function viewPendingInvitationEmployees(User $user)
    {
        return $user->hasDirectPermissionTwo('view pending invitation employees');
    }

    public function cancelOwnerInvitation(User $user)
    {
        return $user->hasDirectPermissionTwo('cancel owner invitations');
    }

    public function cancelEmployeeInvitation(User $user)
    {
        return $user->hasDirectPermissionTwo('cancel employee invitations');
    }
}
