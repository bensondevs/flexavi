<?php

namespace App\Observers\User;

use App\Models\User\EmailVerification;

class EmailVerificationObserver
{
    /**
     * Handle the EmailVerification "created" event.
     *
     * @param EmailVerification $emailVerification
     * @return void
     */
    public function created(EmailVerification $emailVerification): void
    {
        //
    }

    /**
     * Handle the EmailVerification "created" event.
     *
     * @param EmailVerification $emailVerification
     * @return void
     */
    public function creating(EmailVerification $emailVerification): void
    {
        $emailVerification->code = random_string(10);
    }

    /**
     * Handle the EmailVerification "updated" event.
     *
     * @param EmailVerification $emailVerification
     * @return void
     */
    public function updated(EmailVerification $emailVerification): void
    {
        //
    }

    /**
     * Handle the EmailVerification "deleted" event.
     *
     * @param EmailVerification $emailVerification
     * @return void
     */
    public function deleted(EmailVerification $emailVerification): void
    {
        //
    }

    /**
     * Handle the EmailVerification "restored" event.
     *
     * @param EmailVerification $emailVerification
     * @return void
     */
    public function restored(EmailVerification $emailVerification): void
    {
        //
    }

    /**
     * Handle the EmailVerification "force deleted" event.
     *
     * @param EmailVerification $emailVerification
     * @return void
     */
    public function forceDeleted(EmailVerification $emailVerification): void
    {
        //
    }
}
