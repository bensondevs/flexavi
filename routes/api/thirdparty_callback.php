<?php

use App\Http\Controllers\ThirdPartyCallback\Twilio\TwilioSmsCallbackController;

Route::group(['prefix' => 'twilio'], function () {
    Route::post('sms_callback', [TwilioSmsCallbackController::class, 'handle']);
});
