<?php

namespace App\Policies;

use App\Models\{ User, Subscription };
use Illuminate\Auth\Access\HandlesAuthorization;

class SubscriptionPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        return $user->hasPermissionTo('view any subscriptions');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Subscription  $subscription
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Subscription $subscription)
    {
        return $user->hasCompanyPermission($subscription->company_id, 'view subscriptions');
    }

    /**
     * Determine whether the user can purchase the model
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function purchase(User $user)
    {
        return $user->hasPermissionTo('purchase subscriptions');
    }

    /**
     * Determine whether the user can activate the model
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Subscription  $subscription
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function activate(User $user, Subscription $subscription)
    {
        return $user->hasCompanyPermission($subscription->company_id, 'activate subscriptions');
    }

    /**
     * Determine whether the user can terminate the model
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Subscription  $subscription
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function terminate(User $user, Subscription $subscription)
    {
        return $user->hasCompanyPermission($subscription->company_id, 'terminate subscriptions');
    }
}
