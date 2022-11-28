<?php

namespace App\Policies\Company\Subscription;

use App\Enums\Subscription\SubscriptionStatus;
use App\Models\{Company\Company, Subscription\Subscription, User\User};
use Illuminate\Auth\Access\HandlesAuthorization;

class SubscriptionPolicy
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
        return $user->hasDirectPermissionTwo('view any subscriptions', false);
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param User $user
     * @param Subscription $subscription
     * @return bool
     */
    public function view(User $user, Subscription $subscription): bool
    {
        return $user->hasCompanyDirectPermission($subscription->company_id, 'view subscriptions', false);
    }

    /**
     * Determine whether the user can purchase the model
     *
     * @param User $user
     * @param Company $company
     * @return bool
     */
    public function purchase(User $user, Company $company): bool
    {
        return $user->hasCompanyDirectPermission($company->id, 'purchase subscriptions', false);
    }

    /**
     * Determine whether the user can activate the model
     *
     * @param User $user
     * @param Subscription $subscription
     * @return bool
     */
    public function activate(User $user, Subscription $subscription): bool
    {
        $activeSubscription = Subscription::where('company_id', $subscription->company_id)->whereNotIn('id', [$subscription->id])->where('status', SubscriptionStatus::Active)->exists();
        if ($activeSubscription) {
            abort(422, "Unable to activate this subscription package because other subscription packages are still active, please terminate first for active subscription packages.");
        }

        return $user->hasCompanyDirectPermission($subscription->company_id, 'activate subscriptions', false);
    }

    /**
     * Determine whether the user can terminate the model
     *
     * @param User $user
     * @param Subscription $subscription
     * @return bool
     */
    public function terminate(User $user, Subscription $subscription): bool
    {
        return $user->hasCompanyDirectPermission($subscription->company_id, 'terminate subscriptions', false);
    }
}
