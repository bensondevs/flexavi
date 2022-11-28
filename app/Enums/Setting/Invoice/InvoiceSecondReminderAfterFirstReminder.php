<?php

declare(strict_types=1);

namespace App\Enums\Setting\Invoice;

use BenSampo\Enum\Enum;

/**
 * InvoiceSecondReminderAfterFirstReminder
 */
final class InvoiceSecondReminderAfterFirstReminder extends Enum
{
    public const Default = 4;
    public const ADay = 1;
    public const TwoDay = 2;
    public const ThreeDay = 3;
    public const FourDay = 4;
    public const FiveDay = 5;
    public const SixDay = 6;
    public const SevenDay = 7;
}
