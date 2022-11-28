<?php

namespace App\Helpers;

class MollieHelper
{
    /**
     * Get mollie payment webhook url
     *
     * @return string
     */
    public function paymentWebhookUrl(): string
    {
        return config('mollie.app_url') . '/mollie/payment/webhook';
    }

    /**
     * Get mollie redirect url
     *
     * @return string
     */
    public function redirectUrl(): string
    {
        return config('app.frontend_url');
    }
}
