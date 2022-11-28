<?php declare(strict_types=1);

namespace App\Enums\SubscriptionPayment;

use BenSampo\Enum\Enum;

final class PaymentGatewayType extends Enum
{
    const Mollie = 1;
}
