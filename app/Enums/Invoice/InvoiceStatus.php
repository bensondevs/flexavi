<?php

namespace App\Enums\Invoice;

use BenSampo\Enum\Enum;
use BenSampo\Enum\Contracts\LocalizedEnum;

final class InvoiceStatus extends Enum implements LocalizedEnum
{
    const Created = 1;
    const Send = 2;
    const Paid = 3;
    const PaymentOverdue = 4
    const FirstReminder = 5;
    const FirstReminderSent = 6;
    const SecondReminder = 7;
    const SencondReminderSent = 8;
    const ThirdReminder = 9;
    const ThirdReminderSent = 10;
    const OverdueDebtCollector = 11;
    const SentDebtCollector = 12;
    const PaidViaDebtCollector = 13;
}
