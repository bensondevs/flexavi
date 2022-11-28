<?php

declare(strict_types=1);

namespace App\Enums\Setting\Invoice;

use BenSampo\Enum\Enum;

/**
 * InvoiceDefaultPaymentMethod
 */
final class InvoiceDefaultPaymentMethod extends Enum
{
    public const Cash = 1;
    public const BankTransfer = 2;
}
