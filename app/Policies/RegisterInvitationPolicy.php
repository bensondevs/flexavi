<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class RegisterInvitationPolicy
{
    use HandlesAuthorization;

    public function sendEmployeeRegisterInvitation(User $user)
    {
        return $user->hasPermissionTo('send employee register invitations');
    }

    public function sendOwnerRegisterInvitation(User $user)
    {
        return $user->hasPermissionTo('send owner register invitations');
    }
}
