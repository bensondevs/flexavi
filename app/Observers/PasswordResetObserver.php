<?php

namespace App\Observers;

use App\Jobs\Auth\SendResetPasswordToken;
use App\Models\User\PasswordReset;

class PasswordResetObserver
{
    /**
     * Handle the PasswordReset "creating" event.
     *
     * @param PasswordReset $passwordReset
     * @return void
     */
    public function creating(PasswordReset $passwordReset): void
    {
        $passwordReset->id = generateUuid();
        $passwordReset->created_at = now();
        $passwordReset->expired_at = now()->addMinutes(3);
        $passwordReset->token = randomToken(6);
    }

    /**
     * Handle the PasswordReset "created" event.
     *
     * @param PasswordReset $passwordReset
     * @return void
     */
    public function created(PasswordReset $passwordReset): void
    {
        dispatch(new SendResetPasswordToken($passwordReset->user, $passwordReset));
    }

    /**
     * Handle the PasswordReset "updated" event.
     *
     * @param PasswordReset $passwordReset
     * @return void
     */
    public function updated(PasswordReset $passwordReset): void
    {
        //
    }

    /**
     * Handle the PasswordReset "deleted" event.
     *
     * @param PasswordReset $passwordReset
     * @return void
     */
    public function deleted(PasswordReset $passwordReset): void
    {
        //
    }

    /**
     * Handle the PasswordReset "restored" event.
     *
     * @param PasswordReset $passwordReset
     * @return void
     */
    public function restored(PasswordReset $passwordReset): void
    {
        //
    }

    /**
     * Handle the PasswordReset "force deleted" event.
     *
     * @param PasswordReset $passwordReset
     * @return void
     */
    public function forceDeleted(PasswordReset $passwordReset): void
    {
        //
    }
}
