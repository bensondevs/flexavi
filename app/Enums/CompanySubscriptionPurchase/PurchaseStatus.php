<?php

namespace App\Enums\CompanySubscriptionPurchase;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class PurchaseStatus extends Enum
{
    const Pending =   1;
    const Active =   2;
    const WaitingPayment =   3;
    const Stop =   4;
    const Inactive = 5;
}
