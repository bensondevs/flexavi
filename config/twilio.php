<?php

return [
    'TWILIO_SID' => env('TWILIO_SID', ''),
    'TWILIO_AUTH_TOKEN' => env('TWILIO_AUTH_TOKEN', ''),
    'TWILIO_NUMBER' => env('TWILIO_NUMBER', ''),
    'TWILIO_SMS_CALLBACK_URL' => getenv('APP_URL') . '/api/third_party_callbacks/twilio/sms_callback',
];
