<?php

declare(strict_types=1);

namespace App\Enums\Setting\Invoice;

use BenSampo\Enum\Enum;

/**
 * InvoiceAutoSendReminderWhenOnDueDate
 */
final class InvoiceAutoSendReminderWhenOnDueDate extends Enum
{
    public const Off = 1;
    public const On = 2;
}
