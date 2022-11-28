<?php

namespace App\Policies\Company\Mollie;

use App\Models\User\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class MolliePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAnyPaymentMethods(User $user)
    {
        return $user->hasDirectPermissionTwo('view any mollie payment methods');
    }
}
