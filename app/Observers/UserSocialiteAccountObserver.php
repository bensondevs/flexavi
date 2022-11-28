<?php

namespace App\Observers;

use App\Models\User\UserSocialiteAccount;

class UserSocialiteAccountObserver
{
    /**
     * Handle the UserSocialiteAccount "creating" event.
     *
     * @param UserSocialiteAccount $userSocialiteAccount
     * @return void
     */
    public function creating(UserSocialiteAccount $userSocialiteAccount): void
    {
        $userSocialiteAccount->id = generateUuid();
    }

    /**
     * Handle the UserSocialiteAccount "created" event.
     *
     * @param UserSocialiteAccount $userSocialiteAccount
     * @return void
     */
    public function created(UserSocialiteAccount $userSocialiteAccount): void
    {
        //
    }

    /**
     * Handle the UserSocialiteAccount "updated" event.
     *
     * @param UserSocialiteAccount $userSocialiteAccount
     * @return void
     */
    public function updated(UserSocialiteAccount $userSocialiteAccount): void
    {
        //
    }

    /**
     * Handle the UserSocialiteAccount "deleted" event.
     *
     * @param UserSocialiteAccount $userSocialiteAccount
     * @return void
     */
    public function deleted(UserSocialiteAccount $userSocialiteAccount): void
    {
        //
    }

    /**
     * Handle the UserSocialiteAccount "restored" event.
     *
     * @param UserSocialiteAccount $userSocialiteAccount
     * @return void
     */
    public function restored(UserSocialiteAccount $userSocialiteAccount): void
    {
        //
    }

    /**
     * Handle the UserSocialiteAccount "force deleted" event.
     *
     * @param UserSocialiteAccount $userSocialiteAccount
     * @return void
     */
    public function forceDeleted(UserSocialiteAccount $userSocialiteAccount): void
    {
        //
    }
}
