<?php

namespace App\Enums\PaymentReminder;

use BenSampo\Enum\Enum;

final class PaymentReminderStatus extends Enum
{
    /**
     * Payment reminder is created
     * 
     * @var  int
     */
    const Created = 1;

    /**
     * Payment reminder is already sent
     * 
     * @var  int
     */
    const ReminderSent = 2;

    /**
     * Payment reminder is paid
     * 
     * @var  int
     */
    const Paid = 3;

    /**
     * Paid partially
     * 
     * @var  int
     */
    const PaidPartially = 4;
}
