<?php

namespace App\Enums\SubscriptionPayment;

use BenSampo\Enum\Enum;

final class SubscriptionPaymentStatus extends Enum
{
    /**
     * Payment is still unpaid
     *
     * @var int
     */
    const Waiting = 0;

    /**
     * Payment is settled
     *
     * @var int
     */
    const Settled = 1;

    /**
     * Payment is failed
     *
     * @var int
     */
    const Failed = 2;

    /**
     * Payment is refunded
     *
     * @var int
     */
    const Refunded = 3;

    /**
     * Payment is expired
     *
     * @var int
     */
    const Expired = 4;
}
