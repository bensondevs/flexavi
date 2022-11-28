<?php

namespace App\Services\Twilio;

use App\Models\Twilio\TwilioSms;

class TwilioCallbackService
{
    /**
     * Handle status changed of twilio sms
     *
     * @param array $data
     * @return void
     */
    public function handle(array $data): void
    {
        $twilioSms = TwilioSms::where('sid', $data['sid'])->first();
        $twilioSms->status = $data['status'];
        $twilioSms->save();
    }
}
