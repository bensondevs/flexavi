<?php

namespace App\Enums\SubscriptionPaymentApiResponse;

use BenSampo\Enum\Enum;

final class SubscriptionPaymentApiResponseVendor extends Enum
{
    /**
     * Vendor payment gateway is mollie pay
     * 
     * @var int
     */
    const MolliePay = 1;
}
