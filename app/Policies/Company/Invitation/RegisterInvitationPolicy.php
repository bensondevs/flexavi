<?php

namespace App\Policies\Company\Invitation;

use App\Models\User\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class RegisterInvitationPolicy
{
    use HandlesAuthorization;

    public function sendEmployeeRegisterInvitation(User $user)
    {
        return $user->hasDirectPermissionTwo('send employee register invitation');
    }

    public function sendOwnerRegisterInvitation(User $user)
    {
        return $user->hasDirectPermissionTwo('send owner register invitations');
    }
}
