<?php

namespace App\Http\Controllers\Api\Company\Mollie;

use Exception;
use Laravel\Cashier\Mollie\Contracts\GetMolliePayment;

abstract class BaseWebhookController
{
    /**
     * @var GetMolliePayment
     */
    protected GetMolliePayment $getMolliePayment;

    /**
     * AbstractWebhookController constructor.
     *
     * @param GetMolliePayment $getMolliePayment
     */
    public function __construct(GetMolliePayment $getMolliePayment)
    {
        $this->getMolliePayment = $getMolliePayment;
    }

    /**
     * Get the Mollie payment.
     *
     * @param string $id
     * @return null
     * @throws Exception
     */
    public function getPayment(string $id)
    {
        try {
            return $this->getMolliePayment->execute($id);
        } catch (Exception $e) {
            if (!config('app.debug')) {
                return null;
            }
            throw $e;
        }
    }
}
