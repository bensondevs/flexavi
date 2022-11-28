<?php declare(strict_types=1);

namespace App\Enums\SubscriptionPayment;

use BenSampo\Enum\Enum;

final class MolliePaymentMethod extends Enum
{
    const CreditCard = 'creditcard';
    const BankTransfer = 'banktransfer';
    const PayPal = 'paypal';
}
