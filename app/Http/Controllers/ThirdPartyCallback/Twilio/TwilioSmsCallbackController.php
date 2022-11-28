<?php

namespace App\Http\Controllers\ThirdPartyCallback\Twilio;

use App\Http\Controllers\Controller;
use App\Http\Requests\Twilio\SmsStatusChangedRequest;
use App\Services\Twilio\TwilioCallbackService;

class TwilioSmsCallbackController extends Controller
{
    /**
     * Twilio callback service container variable
     *
     * @var TwilioCallbackService
     */
    private TwilioCallbackService $twilioCallbackService;

    /**
     * Controller constructor method
     *
     * @param TwilioCallbackService $twilioCallbackService
     */
    public function __construct(TwilioCallbackService $twilioCallbackService)
    {
        $this->twilioCallbackService = $twilioCallbackService;
    }

    /**
     * handle callback status changed
     *
     * @param SmsStatusChangedRequest $request
     * @return void
     */
    public function handle(SmsStatusChangedRequest $request): void
    {
        $this->twilioCallbackService->handle($request->callbackData());
    }
}
