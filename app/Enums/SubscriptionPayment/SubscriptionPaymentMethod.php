<?php

namespace App\Enums\SubscriptionPayment;

use BenSampo\Enum\Enum;

final class SubscriptionPaymentMethod extends Enum
{
    /**
     * Subscription payment method is through cash
     * 
     * @var int
     */
    const Cash = 1;

    /**
     * Subscription payment method is through bank transfer
     * 
     * @var int
     */
    const BankTransfer = 2;

    /**
     * Subscription payment method is through payment gateway
     * 
     * @var int
     */
    const PaymentGateway = 3;
}