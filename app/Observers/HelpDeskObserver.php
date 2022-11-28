<?php

namespace App\Observers;

use App\Jobs\SendMail;
use App\Mail\HelpDesk\HelpDeskMail;
use App\Models\HelpDesk\HelpDesk;

class HelpDeskObserver
{
    /**
     * Handle the HelpDesk "creating" event.
     *
     * @param HelpDesk $helpDesk
     * @return void
     */
    public function creating(HelpDesk $helpDesk)
    {
        $helpDesk->id = generateUuid();
    }

    /**
     * Handle the HelpDesk "created" event.
     *
     * @param HelpDesk $helpDesk
     * @return void
     */
    public function created(HelpDesk $helpDesk)
    {

        $mail = new HelpDeskMail($helpDesk);
        $send = new SendMail($mail, 'flexavi@admin.com');
        dispatch($send);
    }

    /**
     * Handle the HelpDesk "updated" event.
     *
     * @param HelpDesk $helpDesk
     * @return void
     */
    public function updated(HelpDesk $helpDesk)
    {
        //
    }

    /**
     * Handle the HelpDesk "deleted" event.
     *
     * @param HelpDesk $helpDesk
     * @return void
     */
    public function deleted(HelpDesk $helpDesk)
    {
        //
    }

    /**
     * Handle the HelpDesk "restored" event.
     *
     * @param HelpDesk $helpDesk
     * @return void
     */
    public function restored(HelpDesk $helpDesk)
    {
        //
    }

    /**
     * Handle the HelpDesk "force deleted" event.
     *
     * @param HelpDesk $helpDesk
     * @return void
     */
    public function forceDeleted(HelpDesk $helpDesk)
    {
        //
    }
}
