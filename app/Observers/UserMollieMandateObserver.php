<?php

namespace App\Observers;

use App\Models\User\UserMollieMandate;

class UserMollieMandateObserver
{
    /**
     * Handle the UserMollieMandate "creating" event.
     *
     * @param UserMollieMandate $userMollieMandate
     * @return void
     */
    public function creating(UserMollieMandate $userMollieMandate)
    {
        $userMollieMandate->id = generateUuid();
    }

    /**
     * Handle the UserMollieMandate "created" event.
     *
     * @param UserMollieMandate $userMollieMandate
     * @return void
     */
    public function created(UserMollieMandate $userMollieMandate)
    {
        //
    }

    /**
     * Handle the UserMollieMandate "updated" event.
     *
     * @param UserMollieMandate $userMollieMandate
     * @return void
     */
    public function updated(UserMollieMandate $userMollieMandate)
    {
        //
    }

    /**
     * Handle the UserMollieMandate "deleted" event.
     *
     * @param UserMollieMandate $userMollieMandate
     * @return void
     */
    public function deleted(UserMollieMandate $userMollieMandate)
    {
        //
    }

    /**
     * Handle the UserMollieMandate "restored" event.
     *
     * @param UserMollieMandate $userMollieMandate
     * @return void
     */
    public function restored(UserMollieMandate $userMollieMandate)
    {
        //
    }

    /**
     * Handle the UserMollieMandate "force deleted" event.
     *
     * @param UserMollieMandate $userMollieMandate
     * @return void
     */
    public function forceDeleted(UserMollieMandate $userMollieMandate)
    {
        //
    }
}
