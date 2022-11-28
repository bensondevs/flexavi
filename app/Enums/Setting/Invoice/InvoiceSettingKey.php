<?php

declare(strict_types=1);

namespace App\Enums\Setting\Invoice;

use BenSampo\Enum\Enum;

/**
 * Invoice's setting key
 */
final class InvoiceSettingKey extends Enum
{
    public const StartingNumber = 1;
    public const DefaultPaymentMethod = 2;
    public const DefaultPaymentDueDate = 3;
    public const AutoSendReminderWhenOnDueDate = 4;
    public const FirstReminderAfterDueDate = 5;
    public const SecondReminderAfterFirstReminder = 6;
    public const ThirdReminderAfterSecondReminder = 7;
    public const SendDebtCollectorAfterThirdReminder = 8;
}
