<?php

namespace App\Policies\Company\Analytic;

use App\Models\User\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AnalyticPolicy
{
    use HandlesAuthorization;

    /**
     *  Determine the user has permission / can view any analytics
     *
     * @param User $user
     * @return  bool
     */
    public function viewAny(User $user): bool
    {
        return $user->hasDirectPermissionTwo('view any analytics');
    }
}
