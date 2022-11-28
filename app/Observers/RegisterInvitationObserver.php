<?php

namespace App\Observers;

use App\Enums\RegisterInvitation\RegisterInvitationStatus;
use App\Models\Invitation\RegisterInvitation;

class RegisterInvitationObserver
{
    /**
     * Handle the RegisterInvitation "created" event.
     *
     * @param RegisterInvitation $registerInvitation
     * @return void
     */
    public function created(RegisterInvitation $registerInvitation): void
    {
        //
    }

    /**
     * Handle the RegisterInvitation "created" event.
     *
     * @param RegisterInvitation $registerInvitation
     * @return void
     */
    public function creating(RegisterInvitation $registerInvitation): void
    {
        //
    }

    /**
     * Handle the RegisterInvitation "updated" event.
     *
     * @param RegisterInvitation $registerInvitation
     * @return void
     */
    public function updated(RegisterInvitation $registerInvitation): void
    {
        if ($registerInvitation->wasChanged('status') && $registerInvitation->status == RegisterInvitationStatus::Used) {
            $invitationable = $registerInvitation->invitationable;
            $invitationable->setUsed();
        }
    }

    /**
     * Handle the RegisterInvitation "deleted" event.
     *
     * @param RegisterInvitation $registerInvitation
     * @return void
     */
    public function deleted(RegisterInvitation $registerInvitation): void
    {
        //
    }

    /**
     * Handle the RegisterInvitation "restored" event.
     *
     * @param RegisterInvitation $registerInvitation
     * @return void
     */
    public function restored(RegisterInvitation $registerInvitation): void
    {
        //
    }

    /**
     * Handle the RegisterInvitation "force deleted" event.
     *
     * @param RegisterInvitation $registerInvitation
     * @return void
     */
    public function forceDeleted(RegisterInvitation $registerInvitation): void
    {
        //
    }
}
