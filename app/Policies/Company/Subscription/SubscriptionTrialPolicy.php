<?php

namespace App\Policies\Company\Subscription;

use App\Models\Company\Company;
use App\Models\Subscription\Subscription;
use App\Models\User\User;
use App\Traits\CompanyInputRequest;
use Illuminate\Auth\Access\HandlesAuthorization;

class SubscriptionTrialPolicy
{
    use HandlesAuthorization, CompanyInputRequest;

    /**
     * Determine whether the user can view any models.
     *
     * @param User $user
     * @param Company $company
     * @return bool
     */
    public function startTrial(User $user, Company $company): bool
    {
        $trialSubscription = Subscription::trialSubscription($company)->exists();
        if ($trialSubscription) {
            abort(422, "Unable to start a trial subscription because you already have a trial subscription.");
        }
        return $user->hasDirectPermissionTwo('start trial subscriptions', false);
    }
}
