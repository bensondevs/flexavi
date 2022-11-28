<?php

namespace App\Observers;

use App\Models\Twilio\TwilioSms;

class TwilioSmsObserver
{
    /**
     * Handle the TwilioSms "created" event.
     *
     * @param TwilioSms $twilioSms
     * @return void
     */
    public function created(TwilioSms $twilioSms): void
    {
        //
    }

    /**
     * Handle the TwilioSms "created" event.
     *
     * @param TwilioSms $twilioSms
     * @return void
     */
    public function creating(TwilioSms $twilioSms): void
    {
        $twilioSms->id = generateUuid();
    }

    /**
     * Handle the TwilioSms "updated" event.
     *
     * @param TwilioSms $twilioSms
     * @return void
     */
    public function updated(TwilioSms $twilioSms): void
    {
        //
    }

    /**
     * Handle the TwilioSms "deleted" event.
     *
     * @param TwilioSms $twilioSms
     * @return void
     */
    public function deleted(TwilioSms $twilioSms): void
    {
        //
    }

    /**
     * Handle the TwilioSms "restored" event.
     *
     * @param TwilioSms $twilioSms
     * @return void
     */
    public function restored(TwilioSms $twilioSms): void
    {
        //
    }

    /**
     * Handle the TwilioSms "force deleted" event.
     *
     * @param TwilioSms $twilioSms
     * @return void
     */
    public function forceDeleted(TwilioSms $twilioSms): void
    {
        //
    }
}
