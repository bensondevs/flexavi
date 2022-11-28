<?php

namespace App\Enums\Subscription;

use BenSampo\Enum\Enum;

final class SubscriptionStatus extends Enum
{
    /**
     * Subscription is currently inactive
     *
     * @var int
     */
    const Inactive = 0;

    /**
     * Subscription is currently active
     *
     * @var int
     */
    const Active = 1;

    /**
     * Subscription is currently expired
     *
     * @var int
     */
    const Expired = 2;

    /**
     * Subscription is currently terminated
     *
     * @var int
     */
    const Terminated = 3;
}
