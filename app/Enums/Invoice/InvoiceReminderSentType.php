<?php declare(strict_types=1);

namespace App\Enums\Invoice;

use BenSampo\Enum\Enum;

final class InvoiceReminderSentType extends Enum
{
    const InHouseUser = 1;
    const InHouseUserWithCustomer = 2;
}
