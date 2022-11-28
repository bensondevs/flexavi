<?php

namespace App\Services\Twilio;

use App\Models\Twilio\TwilioSms;
use Twilio\Exceptions\ConfigurationException;
use Twilio\Exceptions\TwilioException;
use Twilio\Rest\Api\V2010\Account\MessageInstance;
use Twilio\Rest\Client;

/**
 * @see \Tests\Unit\Services\Twilio\TwilioService\SendTest
 *      To see service method test
 */
class TwilioService
{
    /**
     * Twilio number
     *
     * @var string
     */
    private string $number;

    /**
     * Twilio client container variable
     *
     * @var Client
     */
    private Client $client;

    /**
     * Service constructor method
     *
     * @throws ConfigurationException
     */
    public function __construct()
    {
        $this->number = config('twilio.TWILIO_NUMBER');
        $this->client = new Client(
            config('twilio.TWILIO_SID'),
            config('twilio.TWILIO_AUTH_TOKEN'),
        );
    }

    /**
     * Send service
     *
     * @param string $message
     * @param string $recipient
     * @return MessageInstance
     * @throws TwilioException
     */
    public function send(string $message, string $recipient): MessageInstance
    {
        $twilio = $this->client->messages->create($recipient, [
            'from' => $this->number,
            'body' => $message,
            'statusCallback' => config('twilio.TWILIO_SMS_CALLBACK_URL')
        ]);

        TwilioSms::create([
            'sid' => $twilio->sid,
            'from' => $twilio->from,
            'to' => $twilio->to,
            'content' => $twilio->body,
            'status' => $twilio->status
        ]);

        return $twilio;
    }
}
