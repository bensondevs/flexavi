<?php

namespace App\Observers;

use App\Models\RegisterInvitation;

use App\Enums\RegisterInvitation\RegisterInvitationStatus;

class RegisterInvitationObserver
{
    /**
     * Handle the RegisterInvitation "created" event.
     *
     * @param  \App\Models\RegisterInvitation  $registerInvitation
     * @return void
     */
    public function created(RegisterInvitation $registerInvitation)
    {
        //
    }

    /**
     * Handle the RegisterInvitation "updated" event.
     *
     * @param  \App\Models\RegisterInvitation  $registerInvitation
     * @return void
     */
    public function updated(RegisterInvitation $registerInvitation)
    {
        //
    }

    /**
     * Handle the RegisterInvitation "deleted" event.
     *
     * @param  \App\Models\RegisterInvitation  $registerInvitation
     * @return void
     */
    public function deleted(RegisterInvitation $registerInvitation)
    {
        //
    }

    /**
     * Handle the RegisterInvitation "restored" event.
     *
     * @param  \App\Models\RegisterInvitation  $registerInvitation
     * @return void
     */
    public function restored(RegisterInvitation $registerInvitation)
    {
        //
    }

    /**
     * Handle the RegisterInvitation "force deleted" event.
     *
     * @param  \App\Models\RegisterInvitation  $registerInvitation
     * @return void
     */
    public function forceDeleted(RegisterInvitation $registerInvitation)
    {
        //
    }
}
