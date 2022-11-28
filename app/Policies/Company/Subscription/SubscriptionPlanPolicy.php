<?php

namespace App\Policies\Company\Subscription;

use App\Models\User\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SubscriptionPlanPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param User $user
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        return $user->hasDirectPermissionTwo('view any subscription plans', false);
    }
}
